<?php

namespace App\Jobs;

use App\Models\{AiContent, ContentPublication, SocialIntegration};
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

    public function handle(Usage $usage): void
    {
        $pub = ContentPublication::with('content.customer')->findOrFail($this->publicationId);

        // Om inte i “queued” längre – lämna
        if ($pub->status !== 'queued') return;

        // Om schemalagd tid ligger i framtiden – vänta (processorjobbet tar den vid rätt tid)
        if ($pub->scheduled_at && $pub->scheduled_at->isFuture()) {
            return;
        }

        $content = $pub->content;
        $integration = SocialIntegration::where('customer_id', $content->customer_id)
            ->where('provider', 'instagram')->firstOrFail();

        $imageUrl = $content->inputs['image_url'] ?? null;
        $caption = trim(($content->title ? $content->title."\n\n" : ''). ($content->body_md ?? ''));

        if (!$imageUrl) {
            $pub->update([
                'status' => 'failed',
                'message' => 'Saknar image_url i innehållets inputs för Instagram.',
            ]);
            return;
        }

        $client = new InstagramClient($integration->access_token);

        $pub->update(['status' => 'processing']);

        try {
            // Steg 1: skapa container
            $container = $client->createImageContainer($integration->ig_user_id, $imageUrl, $caption);
            $creationId = $container['id'] ?? null;
            if (!$creationId) {
                throw new \RuntimeException('Kunde inte skapa IG-container.');
            }

            // Steg 2: publicera
            $resp = $client->publishContainer($integration->ig_user_id, $creationId);

            $pub->update([
                'status' => 'published',
                'external_id' => $resp['id'] ?? null,
                'payload' => ['image_url' => $imageUrl, 'caption' => $caption],
                'message' => 'OK',
            ]);

            $usage->increment($content->customer_id, 'social.publish.instagram');
        } catch (\Throwable $e) {
            $pub->update([
                'status' => 'failed',
                'message' => $e->getMessage(),
            ]);
            throw $e;
        }
    }
}
