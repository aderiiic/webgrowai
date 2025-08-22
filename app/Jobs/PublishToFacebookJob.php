<?php

namespace App\Jobs;

use App\Models\ContentPublication;
use App\Models\SocialIntegration;
use App\Services\ImageGenerator;
use App\Services\Social\FacebookClient;
use App\Support\Usage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Str;

class PublishToFacebookJob implements ShouldQueue
{
    use Queueable;

    public function __construct(public int $publicationId)
    {
        $this->onQueue('social');
    }

    public function handle(Usage $usage, ImageGenerator $images): void
    {
        $pub = ContentPublication::with('content.customer')->findOrFail($this->publicationId);
        if ($pub->status !== 'queued') return;

        $content = $pub->content;
        $integration = SocialIntegration::where('customer_id', $content->customer_id)
            ->where('provider', 'facebook')->firstOrFail();

        $message = trim(($content->title ? $content->title . "\n\n" : '') . ($content->body_md ?? ''));
        $payload = $pub->payload ?? [];

        $pub->update(['status' => 'processing']);

        try {
            $imagesEnabled = config('features.image_generation', false);

            $wantImage = (bool)($payload['image']['generate'] ?? $content->inputs['image']['generate'] ?? false);
            $wantImage = $imagesEnabled && $wantImage;

            $imagePrompt = $payload['image_prompt']
                ?? $payload['image']['prompt']
                ?? $content->inputs['image']['prompt']
                ?? null;

            $client = new FacebookClient($integration->access_token);

            if ($wantImage || ($imagesEnabled && $imagePrompt)) {
                $prompt = $imagePrompt ?: $this->buildAutoPrompt($content->title, $content->body_md ?? '', $content->inputs ?? []);
                $bytes  = $images->generate($prompt, '1536x1024'); // giltig storlek
                $resp   = $client->createPagePhoto($integration->page_id, $bytes, 'image-' . Str::random(8) . '.png', $message);

                $pub->update([
                    'status'      => 'published',
                    'external_id' => $resp['post_id'] ?? $resp['id'] ?? null,
                    'payload'     => array_merge($payload, ['image_used' => true, 'prompt' => $prompt]),
                    'message'     => 'OK (photo)',
                ]);
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

            $usage->increment($content->customer_id, 'ai.publish.facebook');
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

        return trim("Create a compelling social image matching the post.
Title: {$title}
Keywords: {$kw}
Audience: {$aud}
Brand voice: {$voice}
Style: clean, modern, high contrast, web-safe, no text overlays, photographic look");
    }
}
