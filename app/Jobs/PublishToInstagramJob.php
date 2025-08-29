<?php

namespace App\Jobs;

use App\Models\ContentPublication;
use App\Models\SocialIntegration;
use App\Services\ImageGenerator;
use App\Services\Social\InstagramClient;
use App\Support\Usage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class PublishToInstagramJob implements ShouldQueue
{
    use Queueable;

    public function __construct(public int $publicationId)
    {
        $this->onQueue('social');
    }

    public function handle(Usage $usage, ImageGenerator $images): void
    {
        $pub = ContentPublication::findOrFail($this->publicationId);
        if ($pub->status !== 'queued') return;

        $content = $pub->aiContent()->first();
        $customerId = $content?->customer_id;
        $siteId     = $content?->site_id;
        abort_unless($customerId && $siteId, 422);

        // Hämta integration per SAJT
        $integration = SocialIntegration::where('site_id', $siteId)
            ->where('provider', 'instagram')
            ->firstOrFail();

        $caption = trim(($content->title ? $content->title . "\n\n" : '') . ($content->body_md ?? ''));
        $payload = $pub->payload ?? [];

        $pub->update(['status' => 'processing']);

        try {
            $imagesEnabled = config('features.image_generation', false);

            // IG kräver bild/video. Om ingen prompt och inga bilduppladdningar -> generera bild.
            $wantImage = (bool)($payload['image']['generate'] ?? $content->inputs['image']['generate'] ?? true);
            $wantImage = $imagesEnabled && $wantImage;

            $imagePrompt = $payload['image_prompt']
                ?? $payload['image']['prompt']
                ?? $content->inputs['image']['prompt']
                ?? null;

            $client = new InstagramClient($integration->access_token);

            $mediaId = null;
            if ($wantImage || ($imagesEnabled && $imagePrompt)) {
                $prompt = $imagePrompt ?: $this->buildAutoPrompt($content->title, $content->body_md ?? '', $content->inputs ?? []);
                $bytes  = $images->generate($prompt, '1536x1536'); // fyrkantig bild för IG
                $mediaId = $client->uploadImage($integration->ig_user_id, $bytes, 'image/jpeg'); // anpassa MIME om nödvändigt
                $client->publishMedia($integration->ig_user_id, $mediaId, $caption);

                $pub->update([
                    'status'      => 'published',
                    'external_id' => $mediaId,
                    'payload'     => array_merge($payload, ['image_used' => true, 'prompt' => $prompt]),
                    'message'     => 'OK (image)',
                ]);
            } else {
                // Fallback om du stödjer redan uppladdat media i payload
                // $mediaId = $payload['media_id'] ?? null; ...
                throw new \RuntimeException('Instagram kräver bild eller video – ingen bild genererades.');
            }

            $usage->increment($customerId, 'ai.publish.instagram');
            $usage->increment($customerId, 'ai.publish');
        } catch (\Throwable $e) {
            $pub->update(['status' => 'failed', 'message' => $e->getMessage()]);
            throw $e;
        }
    }

    private function buildAutoPrompt(?string $title, string $body, array $inputs): string
    {
        $kw    = implode(', ', $inputs['keywords'] ?? []);
        $voice = $inputs['brand']['voice'] ?? null;
        $aud   = $inputs['audience'] ?? null;

        return trim("Create a modern square photo suitable for Instagram feed.
Title: {$title}
Keywords: {$kw}
Audience: {$aud}
Brand voice: {$voice}
Style: vibrant, aesthetic, minimal text cues, square composition, photographic.");
    }
}
