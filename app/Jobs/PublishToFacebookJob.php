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
use Illuminate\Support\Str;
use Carbon\Carbon;
use Throwable;

class PublishToFacebookJob implements ShouldQueue
{
    use Queueable;

    public int $timeout = 300; // 5 minuter
    public int $tries = 3;
    public int $maxExceptions = 1;

    public function __construct(public int $publicationId)
    {
        $this->onQueue('social');
        $this->delay(now()->addSeconds(30));
    }

    public function handle(Usage $usage, ImageGenerator $images): void
    {
        $pub = ContentPublication::with('content')->find($this->publicationId);
        if (!$pub) {
            Log::warning('[FB] Publication saknas – avbryter', ['pub_id' => $this->publicationId]);
            return;
        }

        if (!in_array($pub->status, ['queued', 'processing'], true)) {
            return;
        }

        $content = $pub->content;
        $customerId = $content?->customer_id;
        $siteId = $content?->site_id;
        abort_unless($customerId && $siteId, 422);

        $integration = SocialIntegration::where('site_id', $siteId)
            ->where('provider', 'facebook')
            ->firstOrFail();

        if ($pub->status === 'queued') {
            $pub->update(['status' => 'processing']);
        }

        try {
            // Formatera meddelandet från AI-innehåll (markdown)
            $message = $this->formatMessage($content);

            $payload = $pub->payload ?? [];
            $imageAssetId = $payload['image_asset_id'] ?? null;
            $scheduledAt = $pub->scheduled_at;

            $client = new FacebookClient($integration->access_token);
            $pageId = $integration->page_id; // Antar att detta finns i integrations-tabellen

            $response = null;
            $updatePayload = $payload;

            // Kontrollera om vi ska schemalägga eller publicera direkt
            $shouldSchedule = $scheduledAt && $scheduledAt->gt(now());

            if ($imageAssetId) {
                // Publicera med bild från bildbank
                $asset = ImageAsset::findOrFail((int)$imageAssetId);
                if ((int)$asset->customer_id !== (int)$customerId) {
                    throw new \RuntimeException('Otillåten bild.');
                }

                $disk = Storage::disk($asset->disk);
                $imageBytes = $disk->get($asset->path);
                $filename = basename($asset->path);

                if ($shouldSchedule) {
                    // Facebook stöder ej schemaläggning med bilder via API – publicera utan bild istället
                    Log::info('[FB] Schemaläggning med bild ej stödd, publicerar text istället', [
                        'pub_id' => $pub->id,
                        'scheduled_at' => $scheduledAt->toISOString()
                    ]);

                    $response = $client->schedulePagePost(
                        $pageId,
                        $message,
                        $scheduledAt->timestamp
                    );
                } else {
                    $response = $client->createPagePhoto(
                        $pageId,
                        $imageBytes,
                        $filename,
                        $message
                    );
                }

                $updatePayload['image_asset_id'] = (int)$imageAssetId;
                $updatePayload['image_bank_path'] = $asset->path;

                if (!$shouldSchedule) {
                    ImageAsset::markUsed((int)$imageAssetId, $pub->id);
                }
            } else {
                // Publicera utan bild (endast text)
                if ($shouldSchedule) {
                    $response = $client->schedulePagePost(
                        $pageId,
                        $message,
                        $scheduledAt->timestamp
                    );
                    Log::info('[FB] Inlägg schemalagt', [
                        'pub_id' => $pub->id,
                        'scheduled_at' => $scheduledAt->toISOString()
                    ]);
                } else {
                    $response = $client->createPagePost($pageId, $message);
                }
            }

            if (!isset($response['id'])) {
                throw new \RuntimeException('Facebook returnerade inget ID: ' . json_encode($response));
            }

            $status = $shouldSchedule ? 'scheduled' : 'published';
            $message_text = $shouldSchedule
                ? 'Schemalagt på Facebook'
                : ($imageAssetId ? 'Publicerat med bild' : 'Publicerat som text');

            $pub->update([
                'status' => $status,
                'external_id' => $response['id'],
                'payload' => $updatePayload,
                'message' => $message_text,
            ]);

            // Räkna användning
            $usage->increment($customerId, 'ai.publish.facebook');
            $usage->increment($customerId, 'ai.publish');

        } catch (Throwable $e) {
            Log::error('[FB] Publicering misslyckades', [
                'pub_id' => $pub->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            $pub->update([
                'status' => 'failed',
                'message' => $e->getMessage()
            ]);

            throw $e;
        }
    }

    /**
     * Formatera markdown-innehåll från AI till ren text lämplig för Facebook
     */
    private function formatMessage($content): string
    {
        $title = $content->title ?? '';
        $body = $content->body_md ?? '';

        // Kombinera titel och innehåll
        $rawText = trim(($title ? $title . "\n\n" : '') . $body);

        if (empty($rawText)) {
            throw new \RuntimeException('Inget innehåll att publicera.');
        }

        // Rensa markdown och formatera för Facebook
        $message = $this->cleanMarkdown($rawText);

        // Begränsa längd (Facebook har gräns på ~63,206 tecken, men vi håller det kortare)
        $message = mb_substr($message, 0, 8000);

        return trim($message);
    }

    /**
     * Rensa markdown och formatera för Facebook
     */
    private function cleanMarkdown(string $input): string
    {
        $text = str_replace(["\r\n", "\r"], "\n", $input);

        // Ta bort markdown kodblock wrapper
        if (str_starts_with(trim($text), '```') && str_ends_with(trim($text), '```')) {
            $text = preg_replace('/^```[a-zA-Z0-9_-]*\n?/', '', trim($text));
            $text = preg_replace("/\n?```$/", '', $text);
        }

        // Konvertera markdown-rubriker till fet text
        $text = preg_replace('/^#{1,6}\s*(.+)$/m', '**$1**', $text);

        // Ta bort markdown-bilder
        $text = preg_replace('/!\[.*?\]\([^)]*\)/s', '', $text);
        $text = preg_replace('/<img[^>]*\/?>/is', '', $text);

        // Konvertera markdown-länkar till plain text med URL
        $text = preg_replace('/\[([^\]]+)\]\(([^)]+)\)/', '$1 ($2)', $text);

        // Konvertera markdown-formatering till Facebook-vänlig text
        $text = preg_replace('/\*\*([^*]+)\*\*/', '*$1*', $text); // fet → *text*
        $text = preg_replace('/\*([^*]+)\*/', '_$1_', $text);      // kursiv → _text_
        $text = preg_replace('/`([^`]+)`/', '"$1"', $text);        // kod → "text"

        // Hantera listor
        $text = preg_replace('/^\s*[\*\-\+]\s+/m', '• ', $text);   // punktlista
        $text = preg_replace('/^\s*\d+\.\s+/m', '• ', $text);      // numrerad lista → punktlista

        // Ta bort metadata-rader (nyckelord, stil, etc.)
        $text = preg_replace('/^\s*(Nyckelord|Keywords|Stil|Style|CTA|Målgrupp|Audience|Brand voice)\s*:\s*.*$/im', '', $text);

        // Behåll hashtags för Facebook
        // $text = preg_replace('/(^|\s)#[\p{L}\p{N}_-]+/u', '$1', $text); // Uncomment för att ta bort hashtags

        // Rensa överfladiga tomrader
        $text = preg_replace('/\n{3,}/', "\n\n", $text);

        // Ta bort leading/trailing whitespace per rad
        $text = preg_replace('/^[ \t]+|[ \t]+$/m', '', $text);

        return trim($text);
    }

    /**
     * Hantera job-fel
     */
    public function failed(Throwable $exception): void
    {
        Log::error('[FB] Job misslyckades permanent', [
            'pub_id' => $this->publicationId,
            'error' => $exception->getMessage(),
        ]);

        $pub = ContentPublication::find($this->publicationId);
        if ($pub) {
            $pub->update([
                'status' => 'failed',
                'message' => 'Job misslyckades: ' . $exception->getMessage(),
            ]);
        }
    }
}
