<?php

namespace App\Jobs;

use App\Models\ContentPublication;
use App\Models\ImageAsset;
use App\Models\SocialIntegration;
use App\Services\ImageGenerator;
use App\Services\Social\FacebookClient;
use App\Support\Usage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Throwable;

class PublishToFacebookJob implements ShouldQueue
{
    use Queueable;

    public function __construct(public int $publicationId)
    {
        $this->onQueue('social');
        $this->delay(now()->addSeconds(30));
    }

    public function handle(Usage $usage, ImageGenerator $images): void
    {
        // Läs från write-PDO för att undvika replikations-lag
        $pub = ContentPublication::with('content')->find($this->publicationId);
        if (!$pub) {
            Log::warning('[PublishToLinkedInJob] Publication saknas – avbryter', ['pub_id' => $this->publicationId]);
            return;
        }

        // Enkel retry om rad inte syns direkt (t.ex. pga lag)
        if (!$pub) {
            usleep(250_000); // 250 ms
            $pub = ContentPublication::with('content')
                ->useWritePdo()
                ->find($this->publicationId);

            Log::info('[FB] Hämtad publication (försök 2)', [
                'pub_id' => $this->publicationId,
                'found'  => (bool) $pub,
            ]);
        }

        if (!$pub) {
            Log::warning('[FB] Publication saknas – avbryter gracefully', [
                'pub_id'  => $this->publicationId,
                'message' => 'ContentPublication hittades inte i databasen (kan vara replikerings-lag, fel DB eller borttagen).',
            ]);
            return;
        }

        if (!in_array($pub->status, ['queued', 'processing'], true)) {
            Log::info('[FB] Publication har redan behandlats', [
                'pub_id' => $this->publicationId,
                'status' => $pub->status,
            ]);
            return;
        }

        // Flytta status-övergången till jobbet (minskar race)
        if ($pub->status === 'queued') {
            $pub->update(['status' => 'processing']);
            Log::info('[FB] Satt till processing', ['pub_id' => $this->publicationId]);
        }

        $content = $pub->content;
        if (!$content) {
            Log::warning('[PublishToLinkedInJob] Content saknas', ['pub_id' => $this->publicationId]);
            $pub->update(['status' => 'failed', 'message' => 'Content saknas']);
            return;
        }

        $customerId = $content->customer_id;
        $siteId     = $content->site_id;

        Log::info('[FB] Content laddat', [
            'pub_id'      => $this->publicationId,
            'content_id'  => $content->id,
            'customer_id' => $customerId,
            'site_id'     => $siteId,
        ]);

        if (!$customerId || !$siteId) {
            Log::error('[FB] Customer eller Site ID saknas', [
                'pub_id'      => $this->publicationId,
                'customer_id' => $customerId,
                'site_id'     => $siteId,
            ]);
            $pub->update([
                'status'  => 'failed',
                'message' => 'Customer eller Site ID saknas',
            ]);
            return;
        }

        try {
            $si = SocialIntegration::where('site_id', $siteId)->where('provider', 'facebook')->first();
            if (!$si) {
                throw new \RuntimeException('Ingen Facebook-integration hittad för denna sajt.');
            }

            $accessToken = $si->access_token;
            $pageId      = $si->page_id ?: '';
            if (empty($accessToken) || empty($pageId)) {
                throw new \RuntimeException('Access token eller Page ID saknas för Facebook.');
            }

            $payload     = $pub->payload ?? [];
            $message     = $this->formatMessage($content);
            $imageAssetId = $payload['image_asset_id'] ?? null;
            $scheduledAt  = $pub->scheduled_at;
            $shouldSchedule = $scheduledAt && $scheduledAt->gt(now());

            $fb     = new FacebookClient($accessToken);
            $resp   = null;

            // 1) Bild från bildbank
            if ($imageAssetId) {
                $asset = ImageAsset::find((int)$imageAssetId);
                if ($asset && (int)$asset->customer_id === (int)$customerId) {
                    $disk = Storage::disk($asset->disk);
                    if (!$disk->exists($asset->path)) {
                        throw new \RuntimeException('Bildfil saknas: ' . $asset->path);
                    }

                    $bytes    = $disk->get($asset->path);
                    $filename = basename($asset->path);

                    if ($shouldSchedule) {
                        Log::info('[PublishToFacebookJob] Schemaläggning med bild stöds ej, publicerar text istället', [
                            'pub_id'       => $pub->id,
                            'scheduled_at' => $scheduledAt->toISOString(),
                        ]);
                        $resp = $fb->schedulePagePost($pageId, $message, $scheduledAt->timestamp);
                    } else {
                        $resp = $fb->createPagePhoto($pageId, $bytes, $filename, $message);
                    }

                    $payload['image_asset_id']  = (int)$imageAssetId;
                    $payload['image_bank_path'] = $asset->path;

                    if (!$shouldSchedule) {
                        ImageAsset::markUsed((int)$imageAssetId, $pub->id);
                    }
                } else {
                    Log::warning('[PublishToFacebookJob] Bild saknas eller otillåten', [
                        'pub_id'        => $this->publicationId,
                        'image_asset_id'=> $imageAssetId
                    ]);
                }
            }
            // 2) Publicera text
            else {
                if ($shouldSchedule) {
                    $resp = $fb->schedulePagePost($pageId, $message, $scheduledAt->timestamp);
                    Log::info('[PublishToFacebookJob] Inlägg schemalagt', [
                        'pub_id'       => $pub->id,
                        'scheduled_at' => $scheduledAt->toISOString(),
                    ]);
                } else {
                    $resp = $fb->createPagePost($pageId, $message);
                }
            }

            if (empty($resp) || !isset($resp['id'])) {
                throw new \RuntimeException('Facebook API returnerade tomt svar eller saknar ID. Svar: ' . json_encode($resp));
            }

            $status = $shouldSchedule ? 'scheduled' : 'published';
            $pub->update([
                'status'      => $status,
                'external_id' => $resp['id'],
                'message'     => $shouldSchedule ? 'Schemalagt på Facebook' : 'Publicerat till Facebook',
                'payload'     => $payload,
            ]);

            $usage->increment($customerId, 'ai.publish.facebook');
            $usage->increment($customerId, 'ai.publish');

            Log::info('[PublishToFacebookJob] Publicering slutförd', [
                'pub_id'      => $pub->id,
                'external_id' => $resp['id'],
                'status'      => $status,
            ]);

        } catch (Throwable $e) {
            Log::error('[PublishToFacebookJob] Jobbet misslyckades', [
                'pub_id' => $pub?->id ?? $this->publicationId,
                'error'  => $e->getMessage(),
            ]);
            if ($pub) {
                $pub->update(['status' => 'failed', 'message' => $e->getMessage()]);
            }
            throw $e;
        }
    }

    private function formatMessage($content): string
    {
        $title = $content->title ?? '';
        $body  = $content->body_md ?? '';

        $rawText = trim(($title ? $title . "\n\n" : '') . $body);
        if ($rawText === '') {
            throw new \RuntimeException('Inget innehåll att publicera.');
        }

        $message = $this->cleanMarkdown($rawText);
        return trim(mb_substr($message, 0, 8000));
    }

    private function cleanMarkdown(string $input): string
    {
        $text = str_replace(["\r\n", "\r"], "\n", $input);

        if (str_starts_with(trim($text), '```') && str_ends_with(trim($text), '```')) {
            $text = preg_replace('/^```[a-zA-Z0-9_-]*\n?/', '', trim($text));
            $text = preg_replace("/\n?```$/", '', $text);
        }

        $text = preg_replace('/^#{1,6}\s*(.+)$/m', '**$1**', $text);
        $text = preg_replace('/!\[.*?\]\([^)]*\)/s', '', $text);
        $text = preg_replace('/<img[^>]*\/?>/is', '', $text);
        $text = preg_replace('/\[([^\]]+)\]\(([^)]+)\)/', '$1 ($2)', $text);
        $text = preg_replace('/\*\*([^*]+)\*\*/', '*$1*', $text);
        $text = preg_replace('/\*([^*]+)\*/', '_$1_', $text);
        $text = preg_replace('/`([^`]+)`/', '"$1"', $text);
        $text = preg_replace('/^\s*[\*\-\+]\s+/m', '• ', $text);
        $text = preg_replace('/^\s*\d+\.\s+/m', '• ', $text);
        $text = preg_replace('/^\s*(Nyckelord|Keywords|Stil|Style|CTA|Målgrupp|Audience|Brand voice)\s*:\s*.*$/im', '', $text);
        $text = preg_replace('/\n{3,}/', "\n\n", $text);
        $text = preg_replace('/^[ \t]+|[ \t]+$/m', '', $text);

        return trim($text);
    }

    public function failed(Throwable $exception): void
    {
        Log::error('[FB] Job misslyckades permanent', [
            'pub_id' => $this->publicationId,
            'error'  => $exception->getMessage(),
        ]);

        $pub = ContentPublication::useWritePdo()->find($this->publicationId);
        if ($pub) {
            $pub->update([
                'status'  => 'failed',
                'message' => 'Job misslyckades: ' . $exception->getMessage(),
            ]);
        }
    }
}
