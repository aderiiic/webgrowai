<?php

namespace App\Jobs;

use App\Models\Site;
use App\Support\Usage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class GenerateAiImageJob implements ShouldQueue
{
    use Queueable, InteractsWithQueue;

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
        $site = Site::find($this->siteId);
        if (!$site) {
            Log::warning('[AI Image] Site saknas', ['site_id' => $this->siteId]);
            return;
        }

        $size = $this->mapSize($this->platform);
        $openAiKey = config('services.openai.key', env('OPENAI_API_KEY'));
        if (!$openAiKey) {
            Log::error('[AI Image] OpenAI-nyckel saknas');
            return;
        }

        // Kör DALL·E (Images API)
        $resp = Http::withToken($openAiKey)
            ->asJson()
            ->post('https://api.openai.com/v1/images/generations', [
                'model' => 'dall-e-3',
                'prompt' => $this->prompt,
                'size' => $this->toOpenAiSize($size['w'], $size['h']),
                'n' => 1,
                'response_format' => 'b64_json',
            ]);

        if (!$resp->ok()) {
            Log::error('[AI Image] API-fel', ['status' => $resp->status(), 'body' => $resp->json()]);
            return;
        }

        $b64 = $resp->json('data.0.b64_json');
        if (!$b64) {
            Log::error('[AI Image] Tom bilddata');
            return;
        }

        $binary = base64_decode($b64);

        // Spara till samma struktur som mediabiblioteket använder
        $uuid = (string) Str::uuid();
        $yearMonth = now()->format('Y/m');
        $baseDir   = "customers/{$this->customerId}/images/{$yearMonth}";
        $thumbDir  = "customers/{$this->customerId}/images/thumbs/{$yearMonth}";

        $filename      = "{$uuid}.png";
        $thumbFilename = "{$uuid}.webp";

        $storedPath = $baseDir . '/' . $filename;
        Storage::disk('s3')->put($storedPath, $binary, [
            'visibility'  => 'private',
            'ContentType' => 'image/png',
        ]);

        $mime  = 'image/png';
        $sizeB = strlen($binary);

        $width = $height = null;
        if (function_exists('getimagesizefromstring')) {
            $info = @getimagesizefromstring($binary);
            $width  = $info[0] ?? null;
            $height = $info[1] ?? null;
        }

        // Skapa thumbnail (webp 256x256)
        $thumbPath = null;
        try {
            // Använd samma bildhantering som mediabiblioteket (Intervention v3‑stil)
            $manager = \Intervention\Image\ImageManager::withDriver('gd');
            $thumbImg = $manager->read($binary)->cover(256, 256)->toWebp(85);

            $thumbStored = $thumbDir . '/' . $thumbFilename;
            Storage::disk('s3')->put($thumbStored, (string) $thumbImg, [
                'visibility'  => 'private',
                'ContentType' => 'image/webp',
            ]);
            $thumbPath = $thumbStored;
        } catch (\Throwable $e) {
            Log::warning('[AI Image] Thumbnail misslyckades', ['err' => $e->getMessage()]);
        }

        // Skapa ImageAsset‑post så att MediaPicker ser filen direkt
        try {
            if (class_exists(\App\Models\ImageAsset::class)) {
                \App\Models\ImageAsset::create([
                    'customer_id'   => $this->customerId,
                    'uploaded_by'   => null, // systemgenererad
                    'disk'          => 's3',
                    'path'          => $storedPath,
                    'thumb_path'    => $thumbPath,
                    'original_name' => $filename,
                    'mime'          => $mime,
                    'size_bytes'    => $sizeB,
                    'width'         => $width,
                    'height'        => $height,
                    'sha256'        => hash('sha256', $binary),
                ]);
            }
        } catch (\Throwable $e) {
            Log::warning('[AI Image] Kunde inte skapa ImageAsset', ['err' => $e->getMessage()]);
        }

        // Logga förbrukning
        $usage->increment($this->customerId, 'ai.images');
    }

    private function mapSize(string $platform): array
    {
        return match ($platform) {
            'facebook_square' => ['w' => 1080, 'h' => 1080],
            'facebook_story'  => ['w' => 1080, 'h' => 1920],
            'instagram'       => ['w' => 1080, 'h' => 1350],
            'linkedin'        => ['w' => 1080, 'h' => 1080],
            'blog'            => ['w' => 1200, 'h' => 628],
            default           => ['w' => 1080, 'h' => 1080],
        };
    }

    private function toOpenAiSize(int $w, int $h): string
    {
        $ratio = $w / max(1, $h);
        if (abs($ratio - 1.0) < 0.05) {
            return '1024x1024';
        }
        if ($h > $w) {
            return '1024x1792';
        }
        return '1792x1024';
    }
}
