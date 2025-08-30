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
        $this->afterCommit();
    }

    public function handle(Usage $usage, ImageGenerator $images): void
    {
        Log::info('[PublishToFacebookJob] Start', ['publication_id' => $this->publicationId]);

        $pub = ContentPublication::with('content')->find($this->publicationId);

        if (!$pub) {
            Log::warning('[PublishToFacebookJob] Publication saknas', [
                'publication_id' => $this->publicationId,
            ]);
            return;
        }

        if (!in_array($pub->status, ['queued','processing'], true)) {
            Log::info('[PublishToFacebookJob] Avbryter, status tillåts ej', [
                'publication_id' => $pub->id,
                'status' => $pub->status,
            ]);
            return;
        }

        if ($pub->status === 'queued') {
            $pub->update(['status' => 'processing']);
        }

        try {
            $content    = $pub->content;
            $customerId = $content?->customer_id;
            $siteId     = $content?->site_id;

            if (!$customerId || !$siteId) {
                throw new \RuntimeException('Saknar customer_id eller site_id på innehållet.');
            }

            $integration = SocialIntegration::where('site_id', $siteId)
                ->where('provider', 'facebook')
                ->first();

            if (!$integration) {
                throw new \RuntimeException('Ingen Facebook-integration hittad för denna sajt.');
            }

            $payload = $pub->payload ?? [];

            // Rensa meddelandet med @symbol:buildCleanMessage
            $message = $this->buildCleanMessage(
                (string)($content->title ?? ''),
                (string)($payload['text'] ?? $content->body_md ?? ''),
                ''
            );

            $imagesEnabled = config('features.image_generation', false);
            $wantImage = (bool)($payload['image']['generate'] ?? $content->inputs['image']['generate'] ?? false);
            $wantImage = $imagesEnabled && $wantImage;

            $imagePrompt = $payload['image_prompt']
                ?? $payload['image']['prompt']
                ?? $content->inputs['image']['prompt']
                ?? null;

            $imageAssetId = (int)($payload['image_asset_id'] ?? 0);

            $client = new FacebookClient($integration->access_token);

            // 1) Om användaren valt en bild från bildbanken: använd den
            if ($imageAssetId > 0) {
                Log::info('[PublishToFacebookJob] Inlägg med vald bild', [
                    'pub_id' => $pub->id,
                    'image_asset_id' => $imageAssetId,
                ]);

                $asset = ImageAsset::find($imageAssetId);
                if (!$asset) {
                    throw new \RuntimeException("Kunde inte hitta bild (#{$imageAssetId}).");
                }
                if (!Storage::disk($asset->disk)->exists($asset->path)) {
                    throw new \RuntimeException("Bildfil hittades inte: {$asset->path}");
                }

                $bytes = Storage::disk($asset->disk)->get($asset->path);
                if (empty($bytes)) {
                    throw new \RuntimeException("Bildfilen är tom eller kunde inte läsas");
                }

                $filename = $asset->original_name ?: (basename($asset->path) ?: ('image_'.$asset->id.'.jpg'));

                $resp = $client->createPagePhoto(
                    $integration->page_id,
                    $bytes,
                    $filename,
                    $message
                );

                $pub->update([
                    'status'      => 'published',
                    'external_id' => $resp['post_id'] ?? $resp['id'] ?? null,
                    'payload'     => array_merge($payload, [
                        'image_used'     => true,
                        'image_asset_id' => $imageAssetId,
                        'message'        => $message,
                    ]),
                    'message'     => 'OK (photo)',
                ]);

                // 2) Annars: generera bild om aktiverat/önskat eller prompt finns
            } elseif ($wantImage || ($imagesEnabled && $imagePrompt)) {
                Log::info('[PublishToFacebookJob] Inlägg med genererad bild', ['pub_id' => $pub->id]);

                $prompt = $imagePrompt ?: $this->buildAutoPrompt($content->title, $content->body_md ?? '', $content->inputs ?? []);
                $bytes  = $images->generate($prompt, '1536x1024');
                $resp   = $client->createPagePhoto($integration->page_id, $bytes, 'image-' . Str::random(8) . '.png', $message);

                $pub->update([
                    'status'      => 'published',
                    'external_id' => $resp['post_id'] ?? $resp['id'] ?? null,
                    'payload'     => array_merge($payload, ['image_used' => true, 'prompt' => $prompt, 'message' => $message]),
                    'message'     => 'OK (photo)',
                ]);

                // 3) Annars: textinlägg
            } else {
                if ($pub->scheduled_at && $pub->scheduled_at->isFuture()) {
                    $resp = $client->schedulePagePost($integration->page_id, $message, $pub->scheduled_at->timestamp);
                } else {
                    $resp = $client->createPagePost($integration->page_id, $message);
                }

                $pub->update([
                    'status'      => 'published',
                    'external_id' => $resp['id'] ?? null,
                    'payload'     => array_merge($payload, ['message' => $message]),
                    'message'     => 'OK',
                ]);
            }

            $usage->increment($customerId, 'ai.publish.facebook');
            $usage->increment($customerId, 'ai.publish');

        } catch (Throwable $e) {
            Log::error('[PublishToFacebookJob] Fel', [
                'publication_id' => $this->publicationId,
                'error' => $e->getMessage(),
            ]);
            if (isset($pub) && $pub) {
                $pub->update(['status' => 'failed', 'message' => $e->getMessage()]);
            }
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
