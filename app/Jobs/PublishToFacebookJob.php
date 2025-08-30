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
        // Log::info('[PublishToFacebookJob] Startar', ['pub_id' => $this->publicationId]);

        $pub = ContentPublication::with('content')->findOrFail($this->publicationId);

//        Log::info('[PublishToFacebookJob] Hittade publication', [
//            'pub_id' => $this->publicationId,
//            'status' => $pub->status,
//            'target' => $pub->target,
//            'scheduled_at' => $pub->scheduled_at?->toISOString()
//        ]);

        if (!in_array($pub->status, ['queued','processing'], true)) {
//            Log::info('[PublishToFacebookJob] Hoppar över, fel status', [
//                'pub_id' => $this->publicationId,
//                'status' => $pub->status
//            ]);
            return;
        }

        // Sätt processing om den inte redan är det (direktpublicering sätter ofta redan detta)
        if ($pub->status === 'queued') {
            $pub->update(['status' => 'processing']);
            // Log::info('[PublishToFacebookJob] Uppdaterat till processing', ['pub_id' => $this->publicationId]);
        }

        try {
            $content = $pub->content;
            $customerId = $content?->customer_id;
            $siteId     = $content?->site_id;

//            Log::info('[PublishToFacebookJob] Content info', [
//                'pub_id' => $this->publicationId,
//                'customer_id' => $customerId,
//                'site_id' => $siteId,
//                'has_content' => !is_null($content)
//            ]);

            if (!$customerId || !$siteId) {
                throw new \RuntimeException('Saknar customer_id eller site_id på innehållet.');
            }

            $integration = SocialIntegration::where('site_id', $siteId)
                ->where('provider', 'facebook')
                ->first();

            if (!$integration) {
//                Log::warning('[PublishToFacebookJob] Ingen Facebook-integration hittad', [
//                    'pub_id' => $this->publicationId,
//                    'site_id' => $siteId
//                ]);
                throw new \RuntimeException('Ingen Facebook‑integration hittad för denna sajt.');
            }

//            Log::info('[PublishToFacebookJob] Hittade Facebook-integration', [
//                'pub_id' => $this->publicationId,
//                'integration_id' => $integration->id,
//                'page_id' => $integration->page_id,
//                'has_access_token' => !empty($integration->access_token)
//            ]);

            $message = trim(($content->title ? $content->title . "\n\n" : '') . ($content->body_md ?? ''));
            $payload = $pub->payload ?? [];

//            Log::info('[PublishToFacebookJob] Förbereder meddelande', [
//                'pub_id' => $this->publicationId,
//                'message_length' => strlen($message),
//                'payload_keys' => array_keys($payload)
//            ]);

            $imagesEnabled = config('features.image_generation', false);
            $wantImage = (bool)($payload['image']['generate'] ?? $content->inputs['image']['generate'] ?? false);
            $wantImage = $imagesEnabled && $wantImage;

            $imagePrompt = $payload['image_prompt']
                ?? $payload['image']['prompt']
                ?? $content->inputs['image']['prompt']
                ?? null;

//            Log::info('[PublishToFacebookJob] Bildhantering', [
//                'pub_id' => $this->publicationId,
//                'images_enabled' => $imagesEnabled,
//                'want_image' => $wantImage,
//                'has_image_prompt' => !empty($imagePrompt),
//                'is_scheduled' => $pub->scheduled_at && $pub->scheduled_at->isFuture()
//            ]);

            $client = new FacebookClient($integration->access_token);

            if ($wantImage || ($imagesEnabled && $imagePrompt)) {
                Log::info('[PublishToFacebookJob] Skapar inlägg med bild', ['pub_id' => $this->publicationId]);

                $prompt = $imagePrompt ?: $this->buildAutoPrompt($content->title, $content->body_md ?? '', $content->inputs ?? []);
                $bytes  = $images->generate($prompt, '1536x1024');
                $resp   = $client->createPagePhoto($integration->page_id, $bytes, 'image-' . Str::random(8) . '.png', $message);

//                Log::info('[PublishToFacebookJob] Facebook foto-svar', [
//                    'pub_id' => $this->publicationId,
//                    'response' => $resp
//                ]);

                $pub->update([
                    'status'      => 'published',
                    'external_id' => $resp['post_id'] ?? $resp['id'] ?? null,
                    'payload'     => array_merge($payload, ['image_used' => true, 'prompt' => $prompt]),
                    'message'     => 'OK (photo)',
                ]);
            } else {
                if ($pub->scheduled_at && $pub->scheduled_at->isFuture()) {
//                    Log::info('[PublishToFacebookJob] Schemalägger inlägg', [
//                        'pub_id' => $this->publicationId,
//                        'timestamp' => $pub->scheduled_at->timestamp
//                    ]);
                    $resp = $client->schedulePagePost($integration->page_id, $message, $pub->scheduled_at->timestamp);
                } else {
                    //Log::info('[PublishToFacebookJob] Skapar omedelbart inlägg', ['pub_id' => $this->publicationId]);
                    $resp = $client->createPagePost($integration->page_id, $message);
                }

//                Log::info('[PublishToFacebookJob] Facebook inläggs-svar', [
//                    'pub_id' => $this->publicationId,
//                    'response' => $resp
//                ]);

                $pub->update([
                    'status'      => 'published',
                    'external_id' => $resp['id'] ?? null,
                    'payload'     => array_merge($payload, ['message' => $message]),
                    'message'     => 'OK',
                ]);
            }

            $usage->increment($customerId, 'ai.publish.facebook');
            $usage->increment($customerId, 'ai.publish');

//            Log::info('[PublishToFacebookJob] Slutfört framgångsrikt', [
//                'pub_id' => $this->publicationId,
//                'external_id' => $pub->fresh()->external_id
//            ]);

        } catch (Throwable $e) {
//            Log::error('[PublishToFacebookJob] Misslyckades', [
//                'pub_id' => $this->publicationId,
//                'error' => $e->getMessage(),
//                'trace' => $e->getTraceAsString()
//            ]);
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
