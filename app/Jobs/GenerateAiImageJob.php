<?php

namespace App\Jobs;

use App\Models\ImageAsset;
use App\Models\Site;
use App\Support\Usage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Throwable;

class GenerateAiImageJob implements ShouldQueue
{
    use Queueable, InteractsWithQueue, SerializesModels;

    public int $tries = 3; // Minska från 5 - DALL-E fel är sällan tillfälliga
    public array $backoff = [60, 300, 900]; // Längre backoff för API-begränsningar
    public int $timeout = 180; // 3 minuter timeout för hela jobbet
    public int $maxExceptions = 3;

    public function __construct(
        public int $customerId,
        public int $siteId,
        public string $prompt,
        public string $platform = 'facebook_square'
    ) {
        $this->onQueue('ai');
        $this->afterCommit();
    }

    public function handle(Usage $usage): void
    {
        Log::info('[AI Image] Startar generering', [
            'customer_id' => $this->customerId,
            'site_id' => $this->siteId,
            'platform' => $this->platform
        ]);

        $site = Site::find($this->siteId);
        if (!$site || $site->customer_id !== $this->customerId) {
            Log::warning('[AI Image] Site saknas eller tillhör fel kund', [
                'site_id' => $this->siteId,
                'customer_id' => $this->customerId
            ]);
            $this->fail(new \Exception('Invalid site'));
            return;
        }

        $openAiKey = config('services.openai.key', env('OPENAI_API_KEY'));
        if (!$openAiKey) {
            Log::error('[AI Image] OpenAI-nyckel saknas');
            $this->fail(new \Exception('OpenAI key missing'));
            return;
        }

        $size = $this->mapSize($this->platform);
        $openAiSize = $this->toOpenAiSize($size['w'], $size['h']);

        try {
            // Förbättrad HTTP-klient med längre timeout specifikt för DALL-E
            $response = Http::withToken($openAiKey)
                ->timeout(120) // 2 minuters timeout för API-anrop
                ->connectTimeout(30) // 30s för att etablera anslutning
                ->retry(2, 1000) // Retry 2 gånger med 1s mellanrum
                ->asJson()
                ->post('https://api.openai.com/v1/images/generations', [
                    'model' => 'dall-e-3',
                    'prompt' => $this->sanitizePrompt($this->prompt),
                    'size' => $openAiSize,
                    'n' => 1,
                    'response_format' => 'b64_json',
                    'quality' => 'standard', // Explicit kvalitet för konsistens
                ]);

            if (!$response->successful()) {
                $errorData = $response->json();
                $errorMessage = $errorData['error']['message'] ?? 'Okänt API-fel';
                $errorCode = $errorData['error']['code'] ?? 'unknown';

                Log::error('[AI Image] OpenAI API-fel', [
                    'status' => $response->status(),
                    'error_code' => $errorCode,
                    'error_message' => $errorMessage,
                    'response_body' => $response->body()
                ]);

                // Olika hantering baserat på feltyp
                if ($response->status() === 429) {
                    // Rate limit - försök igen senare
                    $this->release(300); // Vänta 5 minuter
                    return;
                } elseif ($response->status() >= 400 && $response->status() < 500) {
                    // Client error - försök inte igen
                    $this->fail(new \Exception("OpenAI API error: {$errorMessage}"));
                    return;
                } else {
                    // Server error - försök igen
                    throw new \Exception("OpenAI API error: {$errorMessage}");
                }
            }

            $imageData = $response->json('data.0');
            if (!$imageData || !isset($imageData['b64_json'])) {
                throw new \Exception('Tom bilddata från OpenAI API');
            }

            $b64 = $imageData['b64_json'];
            $revisedPrompt = $imageData['revised_prompt'] ?? null; // DALL-E kan revidera prompten

            if ($revisedPrompt && $revisedPrompt !== $this->prompt) {
                Log::info('[AI Image] OpenAI reviderade prompt', [
                    'original' => substr($this->prompt, 0, 200),
                    'revised' => substr($revisedPrompt, 0, 200)
                ]);
            }

        } catch (Throwable $e) {
            Log::error('[AI Image] Fel vid API-anrop', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            // Om det är timeout eller connection error, försök igen
            if (str_contains($e->getMessage(), 'timeout') ||
                str_contains($e->getMessage(), 'Connection') ||
                str_contains($e->getMessage(), 'cURL error')) {
                throw $e; // Låt job retry-mekanismen hantera det
            }

            $this->fail($e);
            return;
        }

        // Bearbeta bilden
        try {
            $binary = base64_decode($b64);
            if (!$binary) {
                throw new \Exception('Kunde inte dekoda base64-bilddata');
            }

            $imageAsset = $this->storeImage($binary, $size);

            // Logga framgång
            Log::info('[AI Image] Bild genererad framgångsrikt', [
                'customer_id' => $this->customerId,
                'asset_id' => $imageAsset->id,
                'size_bytes' => strlen($binary),
                'dimensions' => "{$size['w']}x{$size['h']}"
            ]);

            // Logga förbrukning
            $usage->increment($this->customerId, 'ai.images');

        } catch (Throwable $e) {
            Log::error('[AI Image] Fel vid bildbearbetning', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            $this->fail($e);
        }
    }

    private function storeImage(string $binary, array $size): ImageAsset
    {
        $uuid = (string) Str::uuid();
        $yearMonth = now()->format('Y/m');
        $baseDir = "customers/{$this->customerId}/images/{$yearMonth}";
        $thumbDir = "customers/{$this->customerId}/images/thumbs/{$yearMonth}";

        $filename = "{$uuid}.png";
        $thumbFilename = "{$uuid}.webp";
        $storedPath = $baseDir . '/' . $filename;

        // Spara huvudbild
        Storage::disk('s3')->put($storedPath, $binary, [
            'visibility' => 'private',
            'ContentType' => 'image/png',
            'CacheControl' => 'max-age=31536000', // 1 år cache
        ]);

        // Få bildmått
        $width = $height = null;
        try {
            if (function_exists('getimagesizefromstring')) {
                $imageInfo = @getimagesizefromstring($binary);
                if ($imageInfo) {
                    $width = $imageInfo[0];
                    $height = $imageInfo[1];
                }
            }
        } catch (Throwable $e) {
            Log::warning('[AI Image] Kunde inte få bildmått', ['error' => $e->getMessage()]);
        }

        // Skapa thumbnail
        $thumbPath = null;
        try {
            if (class_exists(\Intervention\Image\ImageManager::class)) {
                $manager = \Intervention\Image\ImageManager::gd(); // Använd statisk metod för v3
                $thumbImg = $manager->read($binary)
                    ->cover(256, 256)
                    ->toWebp(quality: 85);

                $thumbStored = $thumbDir . '/' . $thumbFilename;
                Storage::disk('s3')->put($thumbStored, (string) $thumbImg, [
                    'visibility' => 'private',
                    'ContentType' => 'image/webp',
                    'CacheControl' => 'max-age=31536000',
                ]);
                $thumbPath = $thumbStored;
            }
        } catch (Throwable $e) {
            Log::warning('[AI Image] Thumbnail misslyckades', ['error' => $e->getMessage()]);
        }

        // Skapa ImageAsset
        $imageAsset = ImageAsset::create([
            'customer_id' => $this->customerId,
            'uploaded_by' => null, // AI-genererad
            'disk' => 's3',
            'path' => $storedPath,
            'thumb_path' => $thumbPath,
            'original_name' => "ai-generated-{$this->platform}-{$uuid}.png",
            'mime' => 'image/png',
            'size_bytes' => strlen($binary),
            'width' => $width,
            'height' => $height,
            'sha256' => hash('sha256', $binary),
            'metadata' => json_encode([
                'ai_generated' => true,
                'platform' => $this->platform,
                'prompt_length' => strlen($this->prompt),
                'dall_e_model' => 'dall-e-3',
                'generated_at' => now()->toISOString(),
            ]),
        ]);

        return $imageAsset;
    }

    private function sanitizePrompt(string $prompt): string
    {
        // Begränsa promptlängd (DALL-E har begränsningar)
        $maxLength = 4000;
        if (strlen($prompt) > $maxLength) {
            $prompt = substr($prompt, 0, $maxLength - 10) . '...';
            Log::info('[AI Image] Prompt trunkerad', ['original_length' => strlen($this->prompt)]);
        }

        // Ta bort potentiellt problematiska tecken
        $prompt = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/', '', $prompt);

        return trim($prompt);
    }

    private function mapSize(string $platform): array
    {
        return match ($platform) {
            'facebook_square' => ['w' => 1080, 'h' => 1080],
            'facebook_story' => ['w' => 1080, 'h' => 1920],
            'instagram' => ['w' => 1080, 'h' => 1350],
            'linkedin' => ['w' => 1080, 'h' => 1080],
            'blog' => ['w' => 1200, 'h' => 628],
            default => ['w' => 1080, 'h' => 1080],
        };
    }

    private function toOpenAiSize(int $w, int $h): string
    {
        $ratio = $w / max(1, $h);

        // Kvadratisk (1:1)
        if (abs($ratio - 1.0) < 0.1) {
            return '1024x1024';
        }

        // Porträtt (högre än bred)
        if ($ratio < 0.9) {
            return '1024x1792';
        }

        // Landskap (bredare än hög)
        return '1792x1024';
    }

    public function failed(Throwable $exception): void
    {
        Log::error('[AI Image] Job misslyckades slutgiltigt', [
            'customer_id' => $this->customerId,
            'site_id' => $this->siteId,
            'platform' => $this->platform,
            'error' => $exception->getMessage(),
            'attempts' => $this->attempts
        ]);

        // Här kan du lägga till notification till användaren om jobbet misslyckades
        // t.ex. skicka event som frontend kan lyssna på
    }

    public function retryUntil(): \DateTime
    {
        // Sluta försöka efter 1 timme
        return now()->addHour();
    }
}
