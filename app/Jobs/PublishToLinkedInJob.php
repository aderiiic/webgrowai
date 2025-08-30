<?php

namespace App\Jobs;

use App\Models\ContentPublication;
use App\Models\ImageAsset;
use App\Models\SocialIntegration;
use App\Services\ImageGenerator;
use App\Services\Social\LinkedInService;
use App\Support\Usage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Throwable;

class PublishToLinkedInJob implements ShouldQueue
{
    use Queueable;

    public function __construct(public int $publicationId)
    {
        $this->onQueue('social');
        $this->afterCommit = true;
    }

    public function handle(LinkedInService $li, ImageGenerator $images, Usage $usage): void
    {
        Log::info('[PublishToLinkedInJob] Startar', ['pub_id' => $this->publicationId]);

        $pub = ContentPublication::with('content')->find($this->publicationId);
        if (!$pub) {
            Log::warning('[PublishToLinkedInJob] Publication saknas – avbryter', ['pub_id' => $this->publicationId]);
            return;
        }

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
            $content    = $pub->content;
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

            $accessToken = $si->access_token;
            $ownerUrn    = $si->page_id ?: '';
            if (empty($accessToken)) {
                throw new \RuntimeException('Access token är tomt för LinkedIn-integrationen.');
            }
            if (empty($ownerUrn)) {
                throw new \RuntimeException('Owner URN är tomt för LinkedIn-integrationen.');
            }

            $payload = $pub->payload ?? [];
            $rawText = $payload['text'] ?? (($content?->title ? $content->title."\n\n" : '').($content?->body_md ?? ''));
            $text = $this->cleanSocialText($rawText);
            $text = mb_substr($text, 0, 3000);

            $assetUrn = null;
            $imagesEnabled = config('features.image_generation', false);
            $imageAssetId  = $payload['image_asset_id'] ?? null;

            if ($imageAssetId) {
                $asset = ImageAsset::findOrFail((int)$imageAssetId);
                if ((int)$asset->customer_id !== (int)$customerId) {
                    throw new \RuntimeException('Otillåten bild.');
                }
                $bytes = Storage::disk($asset->disk)->get($asset->path);

                $reg = $li->registerImageUpload($accessToken, $ownerUrn);
                if (!empty($reg['uploadUrl']) && !empty($reg['asset'])) {
                    $mime = $asset->mime ?: 'image/jpeg';
                    $li->uploadToLinkedInUrl($reg['uploadUrl'], $bytes, $mime);
                    $assetUrn = $reg['asset'];
                    $payload['image_asset_id']  = (int)$imageAssetId;
                    $payload['image_bank_path'] = $asset->path;
                }
            } else {
                $want = (bool)($payload['image']['generate'] ?? $content->inputs['image']['generate'] ?? false);
                $want = $imagesEnabled && $want;

                $prompt = $payload['image_prompt']
                    ?? $payload['image']['prompt']
                    ?? $content->inputs['image']['prompt']
                    ?? null;

                if ($want || ($imagesEnabled && $prompt)) {
                    try {
                        $prompt = $prompt ?: $this->buildAutoPrompt($content->title, $content->inputs ?? []);
                        $bytes = $images->generate($prompt, '1536x1024');
                        $reg = $li->registerImageUpload($accessToken, $ownerUrn);
                        if (!empty($reg['uploadUrl']) && !empty($reg['asset'])) {
                            $li->uploadToLinkedInUrl($reg['uploadUrl'], $bytes, 'image/png');
                            $assetUrn = $reg['asset'];
                            $payload = array_merge($payload, ['prompt' => $prompt]);
                        }
                    } catch (Throwable $imageError) {
                        Log::error('[PublishToLinkedInJob] Bildhantering misslyckades', [
                            'pub_id' => $this->publicationId,
                            'error' => $imageError->getMessage(),
                        ]);
                    }
                }
            }

            Log::info('[PublishToLinkedInJob] Anropar LinkedIn publishPost API', [
                'pub_id' => $this->publicationId,
                'has_image' => !empty($assetUrn),
                'text_preview' => substr($text, 0, 100) . (strlen($text) > 100 ? '...' : '')
            ]);

            try {
                $resp = $li->publishPost($accessToken, $ownerUrn, $text, $assetUrn);

                if (empty($resp) || (!isset($resp['id']) && !isset($resp['ugcPost']))) {
                    throw new \RuntimeException('LinkedIn API returnerade tomt svar eller saknar ID. Svar: ' . json_encode($resp));
                }

                $pub->update([
                    'status'      => 'published',
                    'external_id' => $resp['id'] ?? ($resp['ugcPost'] ?? null),
                    'message'     => 'Publicerat till LinkedIn',
                    'payload'     => $payload,
                ]);

                if ($imageAssetId) {
                    ImageAsset::markUsed((int)$imageAssetId, $pub->id);
                }

                $usage->increment($customerId, 'ai.publish.linkedin');
                $usage->increment($customerId, 'ai.publish');
            } catch (Throwable $apiError) {
                Log::error('[PublishToLinkedInJob] LinkedIn API-anrop misslyckades', [
                    'pub_id' => $this->publicationId,
                    'api_error' => $apiError->getMessage(),
                ]);
                throw $apiError;
            }

        } catch (Throwable $e) {
            Log::error('[PublishToLinkedInJob] Jobbet misslyckades', [
                'pub_id' => $this->publicationId,
                'error' => $e->getMessage(),
            ]);
            $pub?->update(['status' => 'failed', 'message' => $e->getMessage()]);
            throw $e;
        }
    }

    private function cleanSocialText(string $input): string
    {
        $t = str_replace(["\r\n","\r"], "\n", (string)$input);

        if (str_starts_with(trim($t), '```') && str_ends_with(trim($t), '```')) {
            $t = preg_replace('/^```[a-zA-Z0-9_-]*\n?/','', trim($t));
            $t = preg_replace("/\n?```$/", '', $t);
        }

        $t = preg_replace('/^#{1,6}\s*.+$/m', '', $t);         // rubriker
        $t = preg_replace('/!\[.*?\]\([^)]*\)/s', '', $t);     // MD-bilder
        $t = preg_replace('/<img[^>]*\/?>/is', '', $t);        // HTML-bilder
        $t = preg_replace('/^\s*(Nyckelord|Keywords|Stil|Style|CTA|Målgrupp|Audience|Brand voice)\s*:\s*.*$/im', '', $t);
        $t = preg_replace('/^\s*(?:#[\p{L}\p{N}_-]+(?:\s+|$))+$/um', '', $t); // hashtag-rad
        $t = preg_replace('/(^|\s)#[\p{L}\p{N}_-]+/u', '$1', $t);             // inline hashtags
        $t = preg_replace('/^\s*[\*\-]\s+/m', '- ', $t);        // listpunkter → streck
        $t = preg_replace('/\n{3,}/', "\n\n", $t);
        $t = preg_replace('/^[ \t]+|[ \t]+$/m', '', $t);

        return trim($t);
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
