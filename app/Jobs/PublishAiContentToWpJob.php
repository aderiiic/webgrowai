<?php

namespace App\Jobs;

use App\Models\AiContent;
use App\Models\WpIntegration;
use App\Services\WordPressClient;
use App\Support\Usage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Str;

class PublishAiContentToWpJob implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public int $aiContentId,
        public int $siteId,
        public string $status = 'draft', // draft|publish|future
        public ?string $scheduleAtIso = null // ISO8601 tid om future
    ) {
        $this->onQueue('publish');
    }

    public function handle(Usage $usage): void
    {
        $content = AiContent::with('site')->findOrFail($this->aiContentId);
        $integration = WpIntegration::where('site_id', $this->siteId)->firstOrFail();

        $client = WordPressClient::for($integration);

        $payload = [
            'title'   => $content->title ?: Str::limit(strip_tags($content->body_md ?? ''), 48, '...'),
            'content' => \Illuminate\Support\Str::of($content->body_md ?? '')->markdown(), // MD -> HTML
            'status'  => $this->status,
        ];

        if ($this->status === 'future' && $this->scheduleAtIso) {
            $payload['date'] = $this->scheduleAtIso;
        }

        $client->createPost($payload);

        // Uppdatera state i AI-content (enkelt)
        $content->update([
            'status' => $this->status === 'draft' ? 'ready' : 'published',
        ]);

        // Usage-tracking: publicering till WP
        $usage->increment($content->customer_id, 'ai.publish.wp');
    }
}
