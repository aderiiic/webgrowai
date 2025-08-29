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
                'site_id' => $siteId
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
                'owner_urn' => $si->page_id
            ]);

            $accessToken = $si->access_token;
            $ownerUrn    = $si->page_id ?: '';

            $payload = $pub->payload ?? [];
            $text = $payload['text'] ?? $content?->title ?? 'Inlägg';

            Log::info('[PublishToLinkedInJob] Förbereder publicering', [
                'pub_id' => $this->publicationId,
                'text_length' => strlen($text),
                'has_access_token' => !empty($accessToken),
                'owner_urn' => $ownerUrn
            ]);

            $assetUrn = null;
            $imagesEnabled = config('features.image_generation', false);

            $want = (bool)($payload['image']['generate'] ?? $content->inputs['image']['generate'] ?? false);
            $want = $imagesEnabled && $want;

            $prompt = $payload['image_prompt']
                ?? $payload['image']['prompt']
                ?? $content->inputs['image']['prompt']
                ?? null;

            if ($want || ($imagesEnabled && $prompt)) {
                Log::info('[PublishToLinkedInJob] Genererar bild', [
                    'pub_id' => $this->publicationId,
                    'prompt' => $prompt
                ]);

                $prompt = $prompt ?: $this->buildAutoPrompt($content->title, $content->inputs ?? []);
                $bytes  = $images->generate($prompt, '1536x1024');

                $reg = $li->registerImageUpload($accessToken, $ownerUrn);
                if (!empty($reg['uploadUrl']) && !empty($reg['asset'])) {
                    $li->uploadToLinkedInUrl($reg['uploadUrl'], $bytes, 'image/png');
                    $assetUrn = $reg['asset'];
                    Log::info('[PublishToLinkedInJob] Bild uppladdad', [
                        'pub_id' => $this->publicationId,
                        'asset_urn' => $assetUrn
                    ]);
                }
            }

            Log::info('[PublishToLinkedInJob] Anropar LinkedIn API', [
                'pub_id' => $this->publicationId,
                'has_image' => !empty($assetUrn)
            ]);

            $resp = $li->publishPost($accessToken, $ownerUrn, $text, $assetUrn);

            Log::info('[PublishToLinkedInJob] LinkedIn API-svar', [
                'pub_id' => $this->publicationId,
                'response' => $resp
            ]);

            $pub->update([
                'status'      => 'published',
                'external_id' => $resp['id'] ?? ($resp['ugcPost'] ?? null),
                'message'     => 'Publicerat till LinkedIn',
                'payload'     => array_merge($payload, ['asset_urn' => $assetUrn]),
            ]);

            $usage->increment($customerId, 'ai.publish.linkedin');
            $usage->increment($customerId, 'ai.publish');

            Log::info('[PublishToLinkedInJob] Slutfört framgångsrikt', ['pub_id' => $this->publicationId]);

        } catch (Throwable $e) {
            Log::error('[PublishToLinkedInJob] Misslyckades', [
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
