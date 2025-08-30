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

    public function __construct(
        public int $aiContentId,
        public int $siteId,
        public string $status = 'draft',
        public ?string $scheduleAtIso = null,
        public ?int $publicationId = null,
        public ?string $preferredProvider = null // 'wordpress' | 'shopify' | null
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

        // Hämta provider via IntegrationManager
        $client   = $integrations->forSite($this->siteId);
        $provider = $client->provider();

        // Spegla target
        if ($this->publicationId) {
            ContentPublication::whereKey($this->publicationId)->update(['target' => $provider]);
        }

        // NYTT: Delegera till WP-specifikt jobb om provider = wordpress
        if ($provider === 'wordpress') {
            // Kopiera image_asset_id från publication payload om det finns
            $existingPayload = $pub->payload ?? [];

            dispatch(new \App\Jobs\PublishAiContentToWpJob(
                aiContentId: $this->aiContentId,
                siteId: $this->siteId,
                status: $this->status,
                scheduleAtIso: $this->scheduleAtIso,
                publicationId: $pub->id
            ))->onQueue('publish');

            // Markera denna rad som "överlåten" så vi inte fortsätter här
            $pub->update(['message' => 'Delegated to WP job']);
            return;
        }

        // Annars kör vi generiskt via adapter (t.ex. Shopify)
        if (!$client->supports('publish')) {
            $pub->update(['status' => 'failed', 'message' => 'Plattformen stödjer ännu inte publicering via API.']);
            return;
        }

        // Rensa innehåll från title-duplikater och bilder innan HTML-konvertering
        $cleanMd = $this->cleanMarkdownForPublishing($content->body_md ?? '', $content->title);
        $html = (string) \Illuminate\Support\Str::of($cleanMd)->markdown();

        $payload = [
            'title'   => $content->title ?: \Illuminate\Support\Str::limit(strip_tags($html), 48, '...'),
            'content' => $html,
            'status'  => $this->status,
        ];

        // Lägg till image_asset_id om det finns från UI
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

    /**
     * Rensa markdown från titel-dubbletter och bilder för generisk publicering
     */
    private function cleanMarkdownForPublishing(string $md, ?string $title = null): string
    {
        if (empty($md)) return $md;

        // Ta bort titel från innehållet om den upprepas
        if ($title) {
            // Ta bort H1-rubriker som matchar titeln (case-insensitive)
            $md = preg_replace('/^#\s+' . preg_quote($title, '/') . '\s*$/im', '', $md);
            // Ta bort exakt matchande titel på egen rad
            $md = preg_replace('/^' . preg_quote($title, '/') . '\s*$/im', '', $md);
        }

        // Ta bort alla H1-rubriker (plattformens titel blir H1)
        $md = preg_replace('/^#\s+.+$/m', '', $md);

        // Ta bort alla bildreferenser (markdown och HTML)
        $md = preg_replace('/!\[.*?\]\([^)]*\)/s', '', $md);           // ![alt](url)
        $md = preg_replace('/<img[^>]*\/?>/is', '', $md);              // <img>
        $md = preg_replace('/\[img[^\]]*\]/is', '', $md);              // [img]

        // Rensa upp extra whitespace
        $md = preg_replace('/\n\s*\n\s*\n/', "\n\n", $md);            // Max 2 tomrader
        $md = preg_replace('/^\s+|\s+$/m', '', $md);                   // Trim rader
        $md = trim($md);

        return $md;
    }
}
