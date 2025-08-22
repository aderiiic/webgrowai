<?php

namespace App\Jobs;

use App\Models\ContentPublication;
use App\Models\SocialIntegration;
use App\Services\ImageGenerator;
use App\Services\Social\InstagramClient;
use App\Support\Usage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PublishToInstagramJob implements ShouldQueue
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
        if ($pub->scheduled_at && $pub->scheduled_at->isFuture()) return;

        $content = $pub->content;
        $integration = SocialIntegration::where('customer_id', $content->customer_id)
            ->where('provider', 'instagram')->firstOrFail();

        $caption = trim(($content->title ? $content->title . "\n\n" : '') . ($content->body_md ?? ''));
        $payload = $pub->payload ?? [];

        $imagesEnabled = config('features.image_generation', false);

        // image_url redan given?
        $imageUrl = $content->inputs['image_url'] ?? $payload['image_url'] ?? null;

        // Annars generera och lägg i S3 temporärt (endast om feature är aktiv)
        $tempKey = null;
        if (!$imageUrl && $imagesEnabled) {
            $want = (bool)($payload['image']['generate'] ?? $content->inputs['image']['generate'] ?? false);
            $want = $imagesEnabled && $want;

            if ($want) {
                $prompt = $payload['image_prompt']
                    ?? $payload['image']['prompt']
                    ?? $content->inputs['image']['prompt']
                    ?? $this->buildAutoPrompt($content->title, $content->inputs ?? []);

                // IG – generera JPEG och ladda upp som image/jpeg
                $jpeg = $images->generateJpeg($prompt, '1024x1024', 90);

                if (!config('filesystems.disks.s3')) {
                    $pub->update(['status' => 'failed', 'message' => 'S3 saknas för temporär IG-bild.']);
                    return;
                }
                $tempKey = 'tmp/ig/' . date('Y/m/') . Str::uuid() . '.jpg';
                Storage::disk('s3')->put($tempKey, $jpeg, ['visibility' => 'public', 'ContentType' => 'image/jpeg']);
                $imageUrl = Storage::disk('s3')->url($tempKey);
            }
        }

        if (!$imageUrl) {
            $pub->update(['status' => 'failed', 'message' => 'Ingen bild tillgänglig för Instagram.']);
            return;
        }

        $client = new InstagramClient($integration->access_token);
        $pub->update(['status' => 'processing']);

        try {
            $container = $client->createImageContainer($integration->ig_user_id, $imageUrl, $caption);
            $creationId = $container['id'] ?? null;
            if (!$creationId) {
                throw new \RuntimeException('Kunde inte skapa IG-container.');
            }

            $resp = $client->publishContainer($integration->ig_user_id, $creationId);

            $pub->update([
                'status'      => 'published',
                'external_id' => $resp['id'] ?? null,
                'payload'     => array_merge($payload, ['image_url' => $imageUrl, 'caption' => $caption]),
                'message'     => 'OK',
            ]);

            $usage->increment($content->customer_id, 'ai.publish.instagram');
            $usage->increment($content->customer_id, 'ai.publish');
        } catch (\Throwable $e) {
            $pub->update(['status' => 'failed', 'message' => $e->getMessage()]);
            throw $e;
        } finally {
            if ($tempKey) {
                try { Storage::disk('s3')->delete($tempKey); } catch (\Throwable) {}
            }
        }
    }

    private function buildAutoPrompt(?string $title, array $inputs): string
    {
        $kw    = implode(', ', $inputs['keywords'] ?? []);
        $voice = $inputs['brand']['voice'] ?? null;
        $aud   = $inputs['audience'] ?? null;

        return trim("Create an Instagram-friendly square image for a social post.
Title: {$title}
Keywords: {$kw}
Audience: {$aud}
Brand voice: {$voice}
Style: vibrant, modern, high-contrast, square 1:1, photographic, no text.");
    }
}
