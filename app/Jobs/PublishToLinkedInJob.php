<?php

namespace App\Jobs;

use App\Models\ContentPublication;
use App\Models\SocialIntegration;
use App\Services\ImageGenerator;
use App\Services\Social\LinkedInService;
use App\Support\Usage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class PublishToLinkedInJob implements ShouldQueue
{
    use Queueable;

    public function __construct(public int $publicationId) {}

    public function handle(LinkedInService $li, ImageGenerator $images, Usage $usage): void
    {
        $pub = \App\Models\ContentPublication::findOrFail($this->publicationId);
        if ($pub->status !== 'queued') return;

        $pub->update(['status' => 'processing']);

        $content = $pub->aiContent()->first();
        $customerId = $content?->customer_id;
        abort_unless($customerId, 422);

        $si = SocialIntegration::where('customer_id', $customerId)->where('provider', 'linkedin')->firstOrFail();
        $accessToken = $si->access_token;
        $ownerUrn    = $si->page_id ?: '';

        $payload = $pub->payload ?? [];
        $text = $payload['text'] ?? $content?->title ?? 'Inlägg';

        $assetUrn = null;
        $imagesEnabled = config('features.image_generation', false);

        $want = (bool)($payload['image']['generate'] ?? $content->inputs['image']['generate'] ?? false);
        $want = $imagesEnabled && $want;

        $prompt = $payload['image_prompt']
            ?? $payload['image']['prompt']
            ?? $content->inputs['image']['prompt']
            ?? null;

        if ($want || ($imagesEnabled && $prompt)) {
            $prompt = $prompt ?: $this->buildAutoPrompt($content->title, $content->inputs ?? []);
            $bytes  = $images->generate($prompt, '1536x1024'); // giltig landskapsstorlek

            $reg = $li->registerImageUpload($accessToken, $ownerUrn);
            if (!empty($reg['uploadUrl']) && !empty($reg['asset'])) {
                $li->uploadToLinkedInUrl($reg['uploadUrl'], $bytes, 'image/png');
                $assetUrn = $reg['asset'];
            }
        }

        $resp = $li->publishPost($accessToken, $ownerUrn, $text, $assetUrn);

        $pub->update([
            'status'      => 'published',
            'external_id' => $resp['id'] ?? ($resp['ugcPost'] ?? null),
            'message'     => 'Publicerat till LinkedIn',
            'payload'     => array_merge($payload, ['asset_urn' => $assetUrn]),
        ]);

        if ($customerId) {
            $usage->increment($customerId, 'ai.publish.linkedin');
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
