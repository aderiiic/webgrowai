<?php
// app/Jobs/PublishAiContentJob.php

namespace App\Jobs;

use App\Models\AiContent;
use App\Models\ContentPublication;
use App\Services\Sites\IntegrationManager;
use App\Support\Usage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class PublishAiContentJob implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public int $aiContentId,
        public int $siteId,
        public string $status = 'draft',
        public ?string $scheduleAtIso = null,
        public ?int $publicationId = null
    ) {
        $this->onQueue('publish');
    }

    public function handle(IntegrationManager $integrations, Usage $usage): void
    {
        $content = AiContent::with('site')->findOrFail($this->aiContentId);

        $pub = $this->publicationId ? ContentPublication::find($this->publicationId) : null;
        if (!$pub) {
            $scheduledAt = null;
            if (!empty($this->scheduleAtIso)) {
                try { $scheduledAt = Carbon::parse($this->scheduleAtIso); } catch (\Throwable) {}
            }

            $pub = ContentPublication::create([
                'ai_content_id' => $content->id,
                'target'        => 'site',
                'status'        => 'queued',
                'scheduled_at'  => $scheduledAt,
                'message'       => null,
                'payload'       => [
                    'site_id' => $this->siteId,
                    'status'  => $this->status,
                    'date'    => $this->scheduleAtIso,
                ],
            ]);
        }

        $pub->update(['status' => 'processing', 'message' => null]);

        $client = $integrations->forSite($this->siteId);

        if (!$client->supports('publish')) {
            $pub->update(['status' => 'failed', 'message' => 'Plattformen stödjer ännu inte publicering via API.']);
            return;
        }

        // Konvertera MD → HTML (enkel fallback). För WP kan blockformat mappas i respektive adapter i framtiden.
        $html = (string) \Illuminate\Support\Str::of($content->body_md ?? '')->markdown();

        $payload = [
            'title'   => $content->title ?: \Illuminate\Support\Str::limit(strip_tags($html), 48, '...'),
            'content' => $html,
            'status'  => $this->status,
        ];
        if ($this->status === 'future' && $this->scheduleAtIso) {
            $payload['date'] = $this->scheduleAtIso;
        }

        try {
            $resp = $client->publish($payload);
            $postId = $resp['id'] ?? null;

            $pub->update([
                'status'      => 'published',
                'external_id' => $postId ? (string)$postId : null,
                'payload'     => $payload,
                'message'     => null,
            ]);

            $content->update([
                'status' => $this->status === 'draft' ? 'ready' : 'published',
            ]);

            $usage->increment($content->customer_id, 'ai.publish.site');
        } catch (\Throwable $e) {
            Log::error('[Publish] misslyckades', ['err' => $e->getMessage()]);
            $pub->update(['status' => 'failed', 'message' => $e->getMessage(), 'payload' => $payload]);
            throw $e;
        }
    }
}
