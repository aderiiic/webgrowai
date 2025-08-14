<?php

namespace App\Jobs;

use App\Models\{AiContent, ContentPublication, SocialIntegration};
use App\Services\Social\FacebookClient;
use App\Support\Usage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class PublishToFacebookJob implements ShouldQueue
{
    use Queueable;

    public function __construct(public int $publicationId)
    {
        $this->onQueue('social');
    }

    public function handle(Usage $usage): void
    {
        $pub = ContentPublication::with('content.customer')->findOrFail($this->publicationId);
        if ($pub->status !== 'queued') return;

        $content = $pub->content;
        $integration = SocialIntegration::where('customer_id', $content->customer_id)
            ->where('provider', 'facebook')->firstOrFail();

        $client = new FacebookClient($integration->access_token);
        $message = trim(($content->title ? $content->title."\n\n" : ''). ($content->body_md ?? ''));

        $pub->update(['status' => 'processing']);

        try {
            if ($pub->scheduled_at && $pub->scheduled_at->isFuture()) {
                $resp = $client->schedulePagePost($integration->page_id, $message, $pub->scheduled_at->timestamp);
            } else {
                $resp = $client->createPagePost($integration->page_id, $message);
            }

            $pub->update([
                'status' => 'published',
                'external_id' => $resp['id'] ?? null,
                'payload' => ['message' => $message],
                'message' => 'OK',
            ]);

            $usage->increment($content->customer_id, 'social.publish.facebook');
        } catch (\Throwable $e) {
            $pub->update([
                'status' => 'failed',
                'message' => $e->getMessage(),
            ]);
            throw $e;
        }
    }
}
