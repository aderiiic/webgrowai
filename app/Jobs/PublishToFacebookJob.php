<?php

namespace App\Jobs;

use App\Models\ContentPublication;
use App\Models\ImageAsset;
use App\Models\SocialIntegration;
use App\Services\ImageGenerator;
use App\Services\Social\FacebookClient;
use App\Support\Usage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Throwable;

class PublishToFacebookJob implements ShouldQueue
{
    use Queueable;

    public function __construct(public int $publicationId)
    {
        $this->onQueue('social');
        // Viktigt: sätt afterCommit via traitens egenskap – deklarera inte egenskapen i klassen
        $this->afterCommit = true;
    }

    public function handle(Usage $usage, ImageGenerator $images): void
    {
        $pub = ContentPublication::with('content')->find($this->publicationId);
        if (!$pub) {
            Log::warning('[FB] Publication saknas – avbryter', ['pub_id' => $this->publicationId]);
            return;
        }

        if (!in_array($pub->status, ['queued','processing'], true)) {
            return;
        }

        if ($pub->status === 'queued') {
            $pub->update(['status' => 'processing']);
        }

        try {
            $content = $pub->content;
            $customerId = $content?->customer_id;
            $siteId     = $content?->site_id;
            if (!$customerId || !$siteId) {
                throw new \RuntimeException('Saknar customer_id eller site_id på innehållet.');
            }

            $integration = SocialIntegration::where('site_id', $siteId)
                ->where('provider', 'facebook')
                ->first();
            if (!$integration) {
                throw new \RuntimeException('Ingen Facebook‑integration hittad för denna sajt.');
            }

            $payload = $pub->payload ?? [];

            // Bygg ren text (ingen MD/hashtags/metablock)
            $message = $this->buildCleanMessage(
                title: (string)($content->title ?? ''),
                md: (string)($content->body_md ?? ''),
                extra: (string)($payload['text'] ?? '')
            );

            $imagesEnabled = config('features.image_generation', false);
            $imageAssetId  = $payload['image_asset_id'] ?? null;

            $client = new FacebookClient($integration->access_token);

            if ($imageAssetId) {
                $asset = ImageAsset::findOrFail((int)$imageAssetId);
                if ((int)$asset->customer_id !== (int)$customerId) {
                    throw new \RuntimeException('Otillåten bild.');
                }

                // Läs bytes och posta som foto-inlägg (caption = message)
                $bytes = Storage::disk($asset->disk)->get($asset->path);
                $filename = 'image-'.Str::random(8).'.'.pathinfo($asset->path, PATHINFO_EXTENSION);
                Log::info('[FB] Laddar upp bild', ['pub_id' => $pub->id, 'filename' => $filename, 'size' => strlen($bytes)]);

                $resp  = $client->createPagePhoto($integration->page_id, $bytes, $filename, $message);

                $payload['image_asset_id']  = (int)$imageAssetId;
                $payload['image_bank_path'] = $asset->path;

                $pub->update([
                    'status'      => 'published',
                    'external_id' => $resp['post_id'] ?? $resp['id'] ?? null,
                    'payload'     => $payload,
                    'message'     => 'OK (photo)',
                ]);

                ImageAsset::markUsed((int)$imageAssetId, $pub->id);
            } else {
                $wantImage = (bool)($payload['image']['generate'] ?? $content->inputs['image']['generate'] ?? false);
                $wantImage = $imagesEnabled && $wantImage;

                $imagePrompt = $payload['image_prompt']
                    ?? $payload['image']['prompt']
                    ?? $content->inputs['image']['prompt']
                    ?? null;

                if ($wantImage || ($imagesEnabled && $imagePrompt)) {
                    $prompt = $imagePrompt ?: $this->buildAutoPrompt($content->title, $content->body_md ?? '', $content->inputs ?? []);
                    $bytes  = $images->generate($prompt, '1536x1024');
                    $resp   = $client->createPagePhoto($integration->page_id, $bytes, 'image-'.Str::random(8).'.png', $message);

                    $pub->update([
                        'status'      => 'published',
                        'external_id' => $resp['post_id'] ?? $resp['id'] ?? null,
                        'payload'     => array_merge($payload, ['image_used' => true, 'prompt' => $prompt]),
                        'message'     => 'OK (photo)',
                    ]);
                } else {
                    $resp = $pub->scheduled_at && $pub->scheduled_at->isFuture()
                        ? $client->schedulePagePost($integration->page_id, $message, $pub->scheduled_at->timestamp)
                        : $client->createPagePost($integration->page_id, $message);

                    $pub->update([
                        'status'      => 'published',
                        'external_id' => $resp['id'] ?? null,
                        'payload'     => array_merge($payload, ['message' => $message]),
                        'message'     => 'OK',
                    ]);
                }
            }

            $usage->increment($customerId, 'ai.publish.facebook');
            $usage->increment($customerId, 'ai.publish');
        } catch (Throwable $e) {
            $pub->update(['status' => 'failed', 'message' => $e->getMessage()]);
            throw $e;
        }
    }

    private function buildCleanMessage(string $title, string $md, string $extra = ''): string
    {
        $raw = trim(($title ? ($title."\n\n") : '').($md ?: '').($extra ? ("\n\n".$extra) : ''));
        $raw = str_replace(["\r\n","\r"], "\n", $raw);
        if (str_starts_with(trim($raw), '```') && str_ends_with(trim($raw), '```')) {
            $raw = preg_replace('/^```[a-zA-Z0-9_-]*\n?/','', trim($raw));
            $raw = preg_replace("/\n?```$/", '', $raw);
        }
        $raw = preg_replace('/^#{1,6}\s*.+$/m', '', $raw);          // rubriker
        $raw = preg_replace('/!\[.*?\]\([^)]*\)/s', '', $raw);       // md-bilder
        $raw = preg_replace('/<img[^>]*\/?>/is', '', $raw);          // html-bilder
        $raw = preg_replace('/^\s*(Nyckelord|Keywords|Stil|Style|CTA|Målgrupp|Audience|Brand voice)\s*:\s*.*$/im', '', $raw);
        $raw = preg_replace('/^\s*(?:#[\p{L}\p{N}_-]+(?:\s+|$))+$/um', '', $raw); // hashtag-rad
        $raw = preg_replace('/(^|\s)#[\p{L}\p{N}_-]+/u', '$1', $raw);             // inline hashtags
        $raw = preg_replace('/^\s*[\*\-]\s+/m', '- ', $raw);          // listpunkter → streck
        $raw = preg_replace('/\n{3,}/', "\n\n", $raw);
        $raw = preg_replace('/^[ \t]+|[ \t]+$/m', '', $raw);
        return mb_substr(trim($raw), 0, 5000);
    }

    private function buildAutoPrompt(?string $title, string $body, array $inputs): string
    {
        $kw    = implode(', ', $inputs['keywords'] ?? []);
        $voice = $inputs['brand']['voice'] ?? null;
        $aud   = $inputs['audience'] ?? null;

        return trim("Create a compelling social image matching the post.
Title: {$title}
Keywords: {$kw}
Audience: {$aud}
Brand voice: {$voice}
Style: clean, modern, high contrast, web-safe, no text overlays, photographic look");
    }
}
