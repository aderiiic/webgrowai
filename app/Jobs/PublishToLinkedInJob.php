<?php

namespace App\Jobs;

use App\Models\ContentPublication;
use App\Models\SocialIntegration;
use App\Services\ImageGenerator;
use App\Services\Social\LinkedInService;
use App\Support\Usage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;
use Throwable;

class PublishToLinkedInJob implements ShouldQueue
{
    use Queueable;

    public function __construct(public int $publicationId)
    {
        $this->onQueue('social');
    }

    public function handle(LinkedInService $li, ImageGenerator $images, Usage $usage): void
    {
        Log::info('[PublishToLinkedInJob] Startar', ['pub_id' => $this->publicationId]);

        $pub = ContentPublication::with('content')->findOrFail($this->publicationId);

        Log::info('[PublishToLinkedInJob] Hittade publication', [
            'pub_id' => $this->publicationId,
            'status' => $pub->status,
            'target' => $pub->target,
            'scheduled_at' => $pub->scheduled_at?->toISOString(),
            'has_content' => !is_null($pub->content)
        ]);

        if (!in_array($pub->status, ['queued','processing'], true)) {
            Log::info('[PublishToLinkedInJob] Hoppar över, fel status', [
                'pub_id' => $this->publicationId,
                'status' => $pub->status
            ]);
            return;
        }

        if ($pub->status === 'queued') {
            $pub->update(['status' => 'processing']);
            Log::info('[PublishToLinkedInJob] Uppdaterat till processing', ['pub_id' => $this->publicationId]);
        }

        try {
            $content = $pub->content;
            $customerId = $content?->customer_id;
            $siteId     = $content?->site_id;

            Log::info('[PublishToLinkedInJob] Content info', [
                'pub_id' => $this->publicationId,
                'customer_id' => $customerId,
                'site_id' => $siteId,
                'content_title' => $content?->title
            ]);

            if (!$customerId || !$siteId) {
                throw new \RuntimeException('Saknar customer_id eller site_id på innehållet.');
            }

            $si = SocialIntegration::where('site_id', $siteId)->where('provider', 'linkedin')->first();
            if (!$si) {
                Log::warning('[PublishToLinkedInJob] Ingen LinkedIn-integration hittad', [
                    'pub_id' => $this->publicationId,
                    'site_id' => $siteId
                ]);
                throw new \RuntimeException('Ingen LinkedIn‑integration hittad för denna sajt.');
            }

            Log::info('[PublishToLinkedInJob] Hittade LinkedIn-integration', [
                'pub_id' => $this->publicationId,
                'integration_id' => $si->id,
                'owner_urn' => $si->page_id,
                'has_access_token' => !empty($si->access_token)
            ]);

            $accessToken = $si->access_token;
            $ownerUrn    = $si->page_id ?: '';

            if (empty($accessToken)) {
                throw new \RuntimeException('Access token är tomt för LinkedIn-integrationen.');
            }

            if (empty($ownerUrn)) {
                throw new \RuntimeException('Owner URN är tomt för LinkedIn-integrationen.');
            }

            $payload = $pub->payload ?? [];
            $text = $payload['text'] ?? $content?->title ?? 'Inlägg';

            if (empty(trim($text))) {
                throw new \RuntimeException('Inläggstext är tom.');
            }

            Log::info('[PublishToLinkedInJob] Förbereder publicering', [
                'pub_id' => $this->publicationId,
                'text_length' => strlen($text),
                'owner_urn' => $ownerUrn,
                'payload_keys' => array_keys($payload)
            ]);

            $assetUrn = null;
            $imagesEnabled = config('features.image_generation', false);

            $want = (bool)($payload['image']['generate'] ?? $content->inputs['image']['generate'] ?? false);
            $want = $imagesEnabled && $want;

            $prompt = $payload['image_prompt']
                ?? $payload['image']['prompt']
                ?? $content->inputs['image']['prompt']
                ?? null;

            Log::info('[PublishToLinkedInJob] Bildhantering', [
                'pub_id' => $this->publicationId,
                'images_enabled' => $imagesEnabled,
                'want_image' => $want,
                'has_prompt' => !empty($prompt)
            ]);

            if ($want || ($imagesEnabled && $prompt)) {
                Log::info('[PublishToLinkedInJob] Börjar bildgenerering', [
                    'pub_id' => $this->publicationId,
                    'prompt' => $prompt
                ]);

                try {
                    $prompt = $prompt ?: $this->buildAutoPrompt($content->title, $content->inputs ?? []);
                    Log::info('[PublishToLinkedInJob] Genererar bild med prompt', [
                        'pub_id' => $this->publicationId,
                        'final_prompt' => $prompt
                    ]);

                    $bytes = $images->generate($prompt, '1536x1024');
                    Log::info('[PublishToLinkedInJob] Bild genererad', [
                        'pub_id' => $this->publicationId,
                        'bytes_length' => strlen($bytes)
                    ]);

                    Log::info('[PublishToLinkedInJob] Registrerar bilduppladdning', ['pub_id' => $this->publicationId]);
                    $reg = $li->registerImageUpload($accessToken, $ownerUrn);

                    Log::info('[PublishToLinkedInJob] Registrerings-svar', [
                        'pub_id' => $this->publicationId,
                        'registration_response' => $reg
                    ]);

                    if (!empty($reg['uploadUrl']) && !empty($reg['asset'])) {
                        Log::info('[PublishToLinkedInJob] Laddar upp bild', ['pub_id' => $this->publicationId]);
                        $li->uploadToLinkedInUrl($reg['uploadUrl'], $bytes, 'image/png');
                        $assetUrn = $reg['asset'];
                        Log::info('[PublishToLinkedInJob] Bild uppladdad', [
                            'pub_id' => $this->publicationId,
                            'asset_urn' => $assetUrn
                        ]);
                    } else {
                        Log::warning('[PublishToLinkedInJob] Bilduppladdning misslyckades - saknar uploadUrl eller asset', [
                            'pub_id' => $this->publicationId,
                            'reg' => $reg
                        ]);
                    }
                } catch (Throwable $imageError) {
                    Log::error('[PublishToLinkedInJob] Bildhantering misslyckades', [
                        'pub_id' => $this->publicationId,
                        'error' => $imageError->getMessage(),
                        'trace' => $imageError->getTraceAsString()
                    ]);
                    // Fortsätt utan bild istället för att misslyckas helt
                }
            }

            Log::info('[PublishToLinkedInJob] Anropar LinkedIn publishPost API', [
                'pub_id' => $this->publicationId,
                'has_image' => !empty($assetUrn),
                'text_preview' => substr($text, 0, 100) . (strlen($text) > 100 ? '...' : '')
            ]);

            try {
                $resp = $li->publishPost($accessToken, $ownerUrn, $text, $assetUrn);

                Log::info('[PublishToLinkedInJob] LinkedIn API-svar', [
                    'pub_id' => $this->publicationId,
                    'response_keys' => array_keys($resp ?? []),
                    'response' => $resp
                ]);

                if (empty($resp) || (!isset($resp['id']) && !isset($resp['ugcPost']))) {
                    throw new \RuntimeException('LinkedIn API returnerade tomt svar eller saknar ID. Svar: ' . json_encode($resp));
                }

                $pub->update([
                    'status'      => 'published',
                    'external_id' => $resp['id'] ?? ($resp['ugcPost'] ?? null),
                    'message'     => 'Publicerat till LinkedIn',
                    'payload'     => array_merge($payload, ['asset_urn' => $assetUrn]),
                ]);

                $usage->increment($customerId, 'ai.publish.linkedin');
                $usage->increment($customerId, 'ai.publish');

                Log::info('[PublishToLinkedInJob] Slutfört framgångsrikt', [
                    'pub_id' => $this->publicationId,
                    'external_id' => $resp['id'] ?? ($resp['ugcPost'] ?? null)
                ]);

            } catch (Throwable $apiError) {
                Log::error('[PublishToLinkedInJob] LinkedIn API-anrop misslyckades', [
                    'pub_id' => $this->publicationId,
                    'api_error' => $apiError->getMessage(),
                    'api_trace' => $apiError->getTraceAsString()
                ]);
                throw $apiError;
            }

        } catch (Throwable $e) {
            Log::error('[PublishToLinkedInJob] Jobbet misslyckades', [
                'pub_id' => $this->publicationId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            $pub->update(['status' => 'failed', 'message' => $e->getMessage()]);
            throw $e;
        }
    }

    private function buildAutoPrompt(?string $title, array $inputs): string
    {
        $kw    = implode(', ', $inputs['keywords'] ?? []);
        $voice = $inputs['brand']['voice'] ?? null;
        $aud   = $inputs['audience'] ?? null;

        return trim("Create a professional social image for LinkedIn feed.
Title: {$title}
Keywords: {$kw}
Audience: {$aud}
Brand voice: {$voice}
Style: clean, minimal, corporate, 1200x630, no text overlays, photographic.");
    }
}
