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

    public int $timeout = 300;
    public int $tries = 3;
    public int $maxExceptions = 1;

    public function __construct(public int $publicationId)
    {
        $this->onQueue('social');
        $this->delay(now()->addSeconds(30));
    }

    public function handle(Usage $usage, ImageGenerator $images): void
    {
        // Ändra från findOrFail() till find() för att undvika exception
        $pub = ContentPublication::with('content')->find($this->publicationId);

        if (!$pub) {
            Log::warning('[FB] Publication saknas – avbryter gracefully', [
                'pub_id' => $this->publicationId,
                'message' => 'ContentPublication hittades inte i databasen'
            ]);
            return; // Avsluta jobbet utan att kasta exception
        }

        // Kontrollera att content finns
        if (!$pub->content) {
            Log::error('[FB] AI Content saknas för publication', ['pub_id' => $this->publicationId]);
            $pub->update([
                'status' => 'failed',
                'message' => 'AI Content saknas för denna publication'
            ]);
            return;
        }

        if (!in_array($pub->status, ['queued', 'processing'], true)) {
            Log::info('[FB] Publication har redan behandlats', [
                'pub_id' => $this->publicationId,
                'status' => $pub->status
            ]);
            return;
        }

        $content = $pub->content;
        $customerId = $content->customer_id;
        $siteId = $content->site_id;

        if (!$customerId || !$siteId) {
            Log::error('[FB] Customer eller Site ID saknas', [
                'pub_id' => $this->publicationId,
                'customer_id' => $customerId,
                'site_id' => $siteId
            ]);
            $pub->update([
                'status' => 'failed',
                'message' => 'Customer eller Site ID saknas'
            ]);
            return;
        }

        $integration = SocialIntegration::where('site_id', $siteId)
            ->where('provider', 'facebook')
            ->first();

        if (!$integration) {
            Log::error('[FB] Facebook integration saknas', [
                'pub_id' => $this->publicationId,
                'site_id' => $siteId
            ]);
            $pub->update([
                'status' => 'failed',
                'message' => 'Facebook integration saknas för denna sajt'
            ]);
            return;
        }

        // Kontrollera att page_id finns
        if (empty($integration->page_id)) {
            Log::error('[FB] Facebook Page ID saknas', [
                'pub_id' => $this->publicationId,
                'integration_id' => $integration->id
            ]);
            $pub->update([
                'status' => 'failed',
                'message' => 'Facebook Page ID saknas i integrationen'
            ]);
            return;
        }

        if ($pub->status === 'queued') {
            $pub->update(['status' => 'processing']);
        }

        try {
            Log::info('[FB] Startar Facebook publicering', [
                'pub_id' => $this->publicationId,
                'content_id' => $content->id,
                'site_id' => $siteId
            ]);

            // Formatera meddelandet från AI-innehåll (markdown)
            $message = $this->formatMessage($content);

            $payload = $pub->payload ?? [];
            $imageAssetId = $payload['image_asset_id'] ?? null;
            $scheduledAt = $pub->scheduled_at;

            $client = new FacebookClient($integration->access_token);
            $pageId = $integration->page_id;

            $response = null;
            $updatePayload = $payload;

            // Kontrollera om vi ska schemalägga eller publicera direkt
            $shouldSchedule = $scheduledAt && $scheduledAt->gt(now());

            if ($imageAssetId) {
                // Publicera med bild från bildbank
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

            Log::info('[FB] Publicering slutförd framgångsrikt', [
                'pub_id' => $pub->id,
                'external_id' => $response['id'],
                'status' => $status
            ]);

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

    public function failed(Throwable $exception): void
    {
        Log::error('[FB] Job misslyckades permanent', [
            'pub_id' => $this->publicationId,
            'error' => $exception->getMessage(),
        ]);

        // Använd find istället för findOrFail här också
        $pub = ContentPublication::find($this->publicationId);
        if ($pub) {
            $pub->update([
                'status' => 'failed',
                'message' => 'Job misslyckades: ' . $exception->getMessage(),
            ]);
        }
    }
}
