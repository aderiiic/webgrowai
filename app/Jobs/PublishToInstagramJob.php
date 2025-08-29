<?php

namespace App\Jobs;

use App\Models\ContentPublication;
use App\Models\ImageAsset;
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
        $pub = ContentPublication::with('content')->findOrFail($this->publicationId);

        if (!in_array($pub->status, ['queued','processing'], true)) {
            return;
        }

        $content = $pub->content;
        $customerId = $content?->customer_id;
        $siteId     = $content?->site_id;
        abort_unless($customerId && $siteId, 422);

        $integration = SocialIntegration::where('site_id', $siteId)
            ->where('provider', 'instagram')
            ->firstOrFail();

        $caption = trim(($content->title ? $content->title . "\n\n" : '') . ($content->body_md ?? ''));
        $payload = $pub->payload ?? [];

        if ($pub->status === 'queued') {
            $pub->update(['status' => 'processing']);
        }

        try {
            $imagesEnabled = config('features.image_generation', false);

            $imageAssetId = $payload['image_asset_id'] ?? null;
            $publicUrl = null;
            $prompt    = null;

            if ($imageAssetId) {
                // Bild från bildbank – skapa temporär URL från S3
                $asset = ImageAsset::findOrFail((int)$imageAssetId);
                if ((int)$asset->customer_id !== (int)$customerId) {
                    throw new \RuntimeException('Otillåten bild.');
                }
                $disk = Storage::disk($asset->disk);
                $publicUrl = $disk->temporaryUrl($asset->path, now()->addMinutes(30));
            } else {
                if (!($payload['image']['generate'] ?? $content->inputs['image']['generate'] ?? true) || !$imagesEnabled) {
                    throw new \RuntimeException('Instagram kräver bild eller video – aktivera bildgenerering eller välj bild från bildbank.');
                }

                $imagePrompt = $payload['image_prompt']
                    ?? $payload['image']['prompt']
                    ?? $content->inputs['image']['prompt']
                    ?? null;

                $client = new InstagramClient($integration->access_token);

                $prompt = $imagePrompt ?: $this->buildAutoPrompt($content->title, $content->body_md ?? '', $content->inputs ?? []);
                $bytes  = $images->generate($prompt, '1536x1536');
                $filename = 'ig/' . now()->format('Y/m/') . Str::uuid()->toString() . '.jpg';

                Storage::disk('public')->put($filename, $bytes);
                $publicUrl = asset(Storage::url($filename));
            }

            $client = new InstagramClient($integration->access_token);
            $container = $client->createImageContainer($integration->ig_user_id, $publicUrl, $caption);
            $creationId = $container['id'] ?? null;
            if (!$creationId) {
                throw new \RuntimeException('Kunde inte skapa Instagram-container.');
            }

            $resp = $client->publishContainer($integration->ig_user_id, $creationId);

            $updatePayload = $payload;
            if ($imageAssetId) {
                $updatePayload['image_asset_id'] = (int)$imageAssetId;
                // Spara även path för att kunna visa thumb i listor (URL löper ut)
                $updatePayload['image_bank_path'] = $asset->path;
            } else {
                $updatePayload['image_used'] = true;
                $updatePayload['prompt'] = $prompt;
                $updatePayload['image_url'] = $publicUrl;
            }

            $pub->update([
                'status'      => 'published',
                'external_id' => $resp['id'] ?? $creationId,
                'payload'     => $updatePayload,
                'message'     => 'OK (image)',
            ]);

            if ($imageAssetId) {
                ImageAsset::markUsed((int)$imageAssetId, $pub->id);
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
