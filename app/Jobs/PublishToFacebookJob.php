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

    public int $timeout = 300;
    public int $tries = 3;
    public int $maxExceptions = 1;

    public function __construct(public int $publicationId)
    {
        $this->onQueue('social');
        $this->delay(now()->addSeconds(30));
        $this->afterCommit();
    }

    public function handle(Usage $usage, ImageGenerator $images): void
    {
        // Läs från write-PDO för att undvika replikations-lag
        $pub = ContentPublication::with('content')
            ->useWritePdo()
            ->find($this->publicationId);

        Log::info('[FB] Hämtad publication (försök 1)', [
            'pub_id' => $this->publicationId,
            'found'  => (bool) $pub,
        ]);

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
            $pub->update(['status' => 'processing', 'message' => null]);
            Log::info('[FB] Satt till processing', ['pub_id' => $this->publicationId]);
        }

        if (!$pub->content) {
            Log::error('[FB] AI Content saknas för publication', [
                'pub_id' => $this->publicationId,
                'pub'    => $pub->only(['id','ai_content_id','target','status']),
            ]);
            $pub->update([
                'status'  => 'failed',
                'message' => 'AI Content saknas för denna publication',
            ]);
            return;
        }

        $content    = $pub->content;
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

        $integration = SocialIntegration::where('site_id', $siteId)
            ->where('provider', 'facebook')
            ->first();

        if (!$integration) {
            Log::error('[FB] Facebook integration saknas', [
                'pub_id' => $this->publicationId,
                'site_id'=> $siteId,
            ]);
            $pub->update([
                'status'  => 'failed',
                'message' => 'Facebook integration saknas för denna sajt',
            ]);
            return;
        }

        if (empty($integration->page_id)) {
            Log::error('[FB] Facebook Page ID saknas', [
                'pub_id'          => $this->publicationId,
                'integration_id'  => $integration->id,
            ]);
            $pub->update([
                'status'  => 'failed',
                'message' => 'Facebook Page ID saknas i integrationen',
            ]);
            return;
        }

        try {
            Log::info('[FB] Startar Facebook publicering', [
                'pub_id'     => $this->publicationId,
                'content_id' => $content->id,
                'site_id'    => $siteId,
            ]);

            $message       = $this->formatMessage($content);
            $payload       = $pub->payload ?? [];
            $imageAssetId  = $payload['image_asset_id'] ?? null;
            $scheduledAt   = $pub->scheduled_at;
            $client        = new FacebookClient($integration->access_token);
            $pageId        = $integration->page_id;

            $response      = null;
            $updatePayload = $payload;

            $shouldSchedule = $scheduledAt && $scheduledAt->gt(now());

            if ($imageAssetId) {
                $asset = ImageAsset::find((int)$imageAssetId);
                if (!$asset) {
                    throw new \RuntimeException('Vald bild hittades inte i bildbanken');
                }
                if ((int)$asset->customer_id !== (int)$customerId) {
                    throw new \RuntimeException('Otillåten åtkomst till bild');
                }

                $disk = Storage::disk($asset->disk);
                if (!$disk->exists($asset->path)) {
                    throw new \RuntimeException('Bildfil saknas: ' . $asset->path);
                }

                $imageBytes = $disk->get($asset->path);
                $filename   = basename($asset->path);

                if ($shouldSchedule) {
                    Log::info('[FB] Schemaläggning med bild ej stödd, publicerar text istället', [
                        'pub_id'       => $pub->id,
                        'scheduled_at' => $scheduledAt->toISOString(),
                    ]);
                    $response = $client->schedulePagePost($pageId, $message, $scheduledAt->timestamp);
                } else {
                    $response = $client->createPagePhoto($pageId, $imageBytes, $filename, $message);
                }

                $updatePayload['image_asset_id']   = (int)$imageAssetId;
                $updatePayload['image_bank_path']  = $asset->path;

                if (!$shouldSchedule) {
                    ImageAsset::markUsed((int)$imageAssetId, $pub->id);
                }
            } else {
                if ($shouldSchedule) {
                    $response = $client->schedulePagePost($pageId, $message, $scheduledAt->timestamp);
                    Log::info('[FB] Inlägg schemalagt', [
                        'pub_id'       => $pub->id,
                        'scheduled_at' => $scheduledAt->toISOString(),
                    ]);
                } else {
                    $response = $client->createPagePost($pageId, $message);
                }
            }

            if (!isset($response['id'])) {
                throw new \RuntimeException('Facebook returnerade inget ID: ' . json_encode($response));
            }

            $status       = $shouldSchedule ? 'scheduled' : 'published';
            $message_text = $shouldSchedule
                ? 'Schemalagt på Facebook'
                : ($imageAssetId ? 'Publicerat med bild' : 'Publicerat som text');

            $pub->update([
                'status'      => $status,
                'external_id' => $response['id'],
                'payload'     => $updatePayload,
                'message'     => $message_text,
            ]);

            $usage->increment($customerId, 'ai.publish.facebook');
            $usage->increment($customerId, 'ai.publish');

            Log::info('[FB] Publicering slutförd', [
                'pub_id'      => $pub->id,
                'external_id' => $response['id'],
                'status'      => $status,
            ]);

        } catch (Throwable $e) {
            Log::error('[FB] Publicering misslyckades', [
                'pub_id' => $pub?->id ?? $this->publicationId,
                'error'  => $e->getMessage(),
            ]);

            // Falla tillbaka försiktigt om $pub av någon anledning saknas
            if ($pub) {
                $pub->update([
                    'status'  => 'failed',
                    'message' => $e->getMessage(),
                ]);
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
