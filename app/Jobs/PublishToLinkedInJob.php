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
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class PublishToLinkedInJob implements ShouldQueue
{
    use Queueable, InteractsWithQueue;

    public function __construct(public int $publicationId)
    {
        $this->onQueue('social');
        $this->delay(now()->addSeconds(60)); // Delay för att undvika race conditions
    }

    public function handle(LinkedInService $li, ImageGenerator $images, Usage $usage): void
    {
        Log::info('[PublishToLinkedInJob] Startar', ['pub_id' => $this->publicationId]);

        $pub = ContentPublication::with('content')->find($this->publicationId);
        if (!$pub) {
            Log::warning('[PublishToLinkedInJob] Publication saknas – avbryter', ['pub_id' => $this->publicationId]);
            return;
        }

        if ($pub->status === 'cancelled') {
            Log::info('[LI] Avbruten – ingen publicering', ['publication_id' => $pub->id]);
            return;
        }

        if ($pub->scheduled_at) {
            $nowTs = Carbon::now()->getTimestamp();
            $schedTs = $pub->scheduled_at->getTimestamp();
            $delay = max(0, $schedTs - $nowTs);
            if ($delay > 20) {
                Log::info('[LI] För tidigt – release till schematid', [
                    'publication_id' => $pub->id,
                    'delay' => $delay,
                    'scheduled_at' => $pub->scheduled_at->toIso8601String(),
                ]);
                $this->release($delay);
                return;
            }
        }

        if (!in_array($pub->status, ['queued','processing','scheduled'], true)) {
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

        $content = $pub->content;
        if (!$content) {
            Log::warning('[PublishToLinkedInJob] Content saknas', ['pub_id' => $this->publicationId]);
            $pub->update(['status' => 'failed', 'message' => 'Content saknas']);
            return;
        }

        $customerId = $content->customer_id;
        $siteId     = $content->site_id;

        if (!$customerId || !$siteId) {
            $pub->update(['status' => 'failed', 'message' => 'Saknar customer_id eller site_id']);
            return;
        }

        try {
            $si = SocialIntegration::where('site_id', $siteId)->where('provider', 'linkedin')->first();
            if (!$si) {
                throw new \RuntimeException('Ingen LinkedIn‑integration hittad för denna sajt.');
            }

            $accessToken = $si->access_token;
            $ownerUrn    = $si->page_id ?: '';
            if (empty($accessToken) || empty($ownerUrn)) {
                throw new \RuntimeException('Access token eller Owner URN saknas för LinkedIn.');
            }

            $payload = $pub->payload ?? [];
            $rawText = $payload['text'] ?? (($content->title ? $content->title."\n\n" : '').($content->body_md ?? ''));
            $text = mb_substr($this->cleanSocialText($rawText), 0, 3000);

            $assetUrn = null;
            $imagesEnabled = config('features.image_generation', false);
            $imageAssetId  = $payload['image_asset_id'] ?? null;

            // 1) Bild från bildbank
            if ($imageAssetId) {
                $asset = ImageAsset::find((int)$imageAssetId);
                if ($asset && (int)$asset->customer_id === (int)$customerId) {
                    $bytes = Storage::disk($asset->disk)->get($asset->path);
                    $reg = $li->registerImageUpload($accessToken, $ownerUrn);
                    if (!empty($reg['uploadUrl']) && !empty($reg['asset'])) {
                        $li->uploadToLinkedInUrl($reg['uploadUrl'], $bytes, $asset->mime ?: 'image/jpeg');
                        $assetUrn = $reg['asset'];
                        $payload['image_asset_id'] = (int)$imageAssetId;
                        $payload['image_bank_path'] = $asset->path;
                    }
                } else {
                    Log::warning('[PublishToLinkedInJob] Bild saknas eller otillåten', ['pub_id' => $this->publicationId, 'image_asset_id' => $imageAssetId]);
                }
            }
            // 2) Generera bild om aktiverat/önskat
            elseif ($imagesEnabled) {
                $want = (bool)($payload['image']['generate'] ?? $content->inputs['image']['generate'] ?? false);
                $prompt = $payload['image_prompt'] ?? $payload['image']['prompt'] ?? $content->inputs['image']['prompt'] ?? null;
                if ($want || $prompt) {
                    try {
                        $prompt = $prompt ?: $this->buildAutoPrompt($content->title, $content->inputs ?? []);
                        $bytes = $images->generate($prompt, '1536x1024');
                        $reg = $li->registerImageUpload($accessToken, $ownerUrn);
                        if (!empty($reg['uploadUrl']) && !empty($reg['asset'])) {
                            $li->uploadToLinkedInUrl($reg['uploadUrl'], $bytes, 'image/png');
                            $assetUrn = $reg['asset'];
                            $payload['prompt'] = $prompt;
                        }
                    } catch (\Throwable $imgErr) {
                        Log::error('[PublishToLinkedInJob] Bildgenerering misslyckades', ['pub_id' => $this->publicationId, 'error' => $imgErr->getMessage()]);
                    }
                }
            }

            // 3) Publicera text eller bild + text
            $resp = $li->publishPost($accessToken, $ownerUrn, $text, $assetUrn);

            if (empty($resp) || (!isset($resp['id']) && !isset($resp['ugcPost']))) {
                throw new \RuntimeException('LinkedIn API returnerade tomt svar eller saknar ID. Svar: ' . json_encode($resp));
            }

            $liId = $resp['id'] ?? ($resp['ugcPost'] ?? null);
            $externalUrl = null;
            if ($liId) {
                // LinkedIn tillåter direkt URN i URL:en i många fall
                // Ex: https://www.linkedin.com/feed/update/urn:li:ugcPost:XXXXXXXXXXXX
                $externalUrl = "https://www.linkedin.com/feed/update/{$liId}";
            }

            $pub->update([
                'status'       => 'published',
                'external_id'  => $liId,
                'external_url' => $externalUrl,
                'message'      => 'Publicerat till LinkedIn',
                'payload'      => array_merge($payload, ['image_used' => !empty($assetUrn)]),
            ]);

            dispatch(new \App\Jobs\RefreshPublicationMetricsJob($pub->id))
                ->onQueue('metrics')
                ->delay(now()->addSeconds(60))
                ->afterCommit();

            if ($imageAssetId) {
                ImageAsset::markUsed((int)$imageAssetId, $pub->id);
            }

            $usage->increment($customerId, 'ai.publish.linkedin');
            $usage->increment($customerId, 'ai.publish');

        } catch (\Throwable $e) {
            Log::error('[PublishToLinkedInJob] Jobbet misslyckades', [
                'pub_id' => $this->publicationId,
                'error' => $e->getMessage(),
            ]);
            $pub->update(['status' => 'failed', 'message' => $e->getMessage()]);
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
