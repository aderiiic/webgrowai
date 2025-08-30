<?php

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

    public $afterCommit = true;

    public function __construct(
        public int $aiContentId,
        public int $siteId,
        public string $status = 'draft',
        public ?string $scheduleAtIso = null,
        public ?int $publicationId = null,
        public ?string $preferredProvider = null
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
                    'site_id'  => $this->siteId,
                    'status'   => $this->status,
                    'date'     => $this->scheduleAtIso,
                    'provider' => $this->preferredProvider,
                ],
            ]);
        }

        $pub->update(['status' => 'processing', 'message' => null]);

        $client   = $integrations->forSite($this->siteId);
        $provider = $client->provider();

        if ($this->publicationId) {
            ContentPublication::whereKey($this->publicationId)->update(['target' => $provider]);
        }

        if ($provider === 'wordpress') {
            dispatch(new \App\Jobs\PublishAiContentToWpJob(
                aiContentId: $this->aiContentId,
                siteId: $this->siteId,
                status: $this->status,
                scheduleAtIso: $this->scheduleAtIso,
                publicationId: $pub->id
            ))->afterCommit()->onQueue('publish');

            $pub->update(['message' => 'Delegated to WP job']);
            return;
        }

        if (!$client->supports('publish')) {
            $pub->update(['status' => 'failed', 'message' => 'Plattformen stödjer ännu inte publicering via API.']);
            return;
        }

        $cleanMd = $this->cleanMarkdownForPublishing($content->body_md ?? '', $content->title);
        $html = (string) \Illuminate\Support\Str::of($cleanMd)->markdown();

        $payload = [
            'title'   => $content->title ?: \Illuminate\Support\Str::limit(strip_tags($html), 48, '...'),
            'content' => $html,
            'status'  => $this->status,
        ];

        $pubPayload = $pub->payload ?? [];
        if (!empty($pubPayload['image_asset_id'])) {
            $payload['image_asset_id'] = (int)$pubPayload['image_asset_id'];
        }

        if ($this->status === 'future' && $this->scheduleAtIso) {
            $payload['date'] = $this->scheduleAtIso;
        }

        try {
            $resp = $client->publish($payload);
            $postId = $resp['id'] ?? null;

            $pub->update([
                'status'      => 'published',
                'external_id' => $postId ? (string)$postId : null,
                'payload'     => array_merge($pubPayload, $payload),
                'message'     => null,
            ]);

            $content->update([
                'status' => $this->status === 'draft' ? 'ready' : 'published',
            ]);

            $metric = match ($provider) {
                'shopify'   => 'ai.publish.shopify',
                default     => 'ai.publish.site',
            };
            $usage->increment($content->customer_id, $metric);
            $usage->increment($content->customer_id, 'ai.publish');
        } catch (\Throwable $e) {
            Log::error('[Publish] misslyckades', ['err' => $e->getMessage(), 'provider' => $provider]);
            $pub->update(['status' => 'failed', 'message' => $e->getMessage(), 'payload' => array_merge($pubPayload, $payload)]);
            throw $e;
        }
    }

    private function cleanMarkdownForPublishing(string $md, ?string $title = null): string
    {
        if ($md === '') return $md;

        $md = str_replace(["\r\n", "\r"], "\n", $md);

        if (str_starts_with(trim($md), '```') && str_ends_with(trim($md), '```')) {
            $md = preg_replace('/^```[a-zA-Z0-9_-]*\n?/','', trim($md));
            $md = preg_replace("/\n?```$/", '', $md);
        }

        if ($title) {
            $md = preg_replace('/^#\s*' . preg_quote($title, '/') . '\s*\n*/im', '', $md);
            $md = preg_replace('/^' . preg_quote($title, '/') . '\s*\n*/im', '', $md);
        }

        $md = preg_replace('/^#\s+.+$/m', '', $md);
        $md = preg_replace('/!\[.*?\]\([^)]*\)/s', '', $md);
        $md = preg_replace('/<img[^>]*\/?>/is', '', $md);
        $md = preg_replace('/^\s*(Nyckelord|Keywords|Stil|Style|CTA|Målgrupp|Audience|Brand voice)\s*:\s*.*$/im', '', $md);
        $md = preg_replace('/^\s*(?:#[\p{L}\p{N}_-]+(?:\s+|$))+$/um', '', $md);
        $md = preg_replace('/(^|\s)#[\p{L}\p{N}_-]+/u', '$1', $md);
        $md = preg_replace('/\n{3,}/', "\n\n", $md);
        $md = preg_replace('/^[ \t]+|[ \t]+$/m', '', $md);

        return trim($md);
    }
}
