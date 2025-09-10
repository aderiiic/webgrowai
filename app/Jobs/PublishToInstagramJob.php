<?php

namespace App\Jobs;

use App\Models\ContentPublication;
use App\Models\ImageAsset;
use App\Models\SocialIntegration;
use App\Services\ImageGenerator;
use App\Services\Social\InstagramClient;
use App\Support\Usage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PublishToInstagramJob implements ShouldQueue
{
    use Queueable, InteractsWithQueue;

    public function __construct(public int $publicationId)
    {
        $this->onQueue('social');
        $this->delay(now()->addSeconds(60));
    }

    public function handle(Usage $usage, ImageGenerator $images): void
    {
        $pub = ContentPublication::with('content')->find($this->publicationId);
        if (!$pub) {
            Log::warning('[IG] Publication saknas – avbryter', ['pub_id' => $this->publicationId]);
            return;
        }

        if ($pub->status === 'cancelled') {
            Log::info('[IG] Avbruten – ingen publicering', ['publication_id' => $pub->id]);
            return;
        }

        if ($pub->scheduled_at) {
            $nowTs = Carbon::now()->getTimestamp();
            $schedTs = $pub->scheduled_at->getTimestamp();
            $delay = max(0, $schedTs - $nowTs);
            if ($delay > 20) {
                Log::info('[IG] För tidigt – release till schematid', [
                    'publication_id' => $pub->id,
                    'delay' => $delay,
                    'scheduled_at' => $pub->scheduled_at->toIso8601String(),
                ]);
                $this->release($delay);
                return;
            }
        }

        if (!in_array($pub->status, ['queued','processing'], true)) {
            return;
        }

        $content = $pub->content;
        $customerId = $content?->customer_id;
        $siteId     = $content?->site_id;
        abort_unless($customerId && $siteId, 422);

        $integration = SocialIntegration::where('site_id', $siteId)
            ->where('provider', 'instagram')
            ->firstOrFail();

        $rawCaption = trim(($content->title ? $content->title . "\n\n" : '') . ($content->body_md ?? ''));
        $caption = $this->cleanCaption($rawCaption);
        $caption = mb_substr($caption, 0, 2200);

        $payload = $pub->payload ?? [];

        if ($pub->status === 'queued') {
            $pub->update(['status' => 'processing']);
        }

        try {
            $imagesEnabled = config('features.image_generation', false);

            $imageAssetId = $payload['image_asset_id'] ?? null;
            $publicUrl = null;
            $prompt    = null;

            if ($imageAssetId) {
                $asset = ImageAsset::findOrFail((int)$imageAssetId);
                if ((int)$asset->customer_id !== (int)$customerId) {
                    throw new \RuntimeException('Otillåten bild.');
                }
                $disk = Storage::disk($asset->disk);
                $publicUrl = $disk->temporaryUrl($asset->path, now()->addMinutes(30));
            } else {
                if (!($payload['image']['generate'] ?? $content->inputs['image']['generate'] ?? true) || !$imagesEnabled) {
                    throw new \RuntimeException('Instagram kräver bild eller video – aktivera bildgenerering eller välj bild från bildbank.');
                }

                $imagePrompt = $payload['image_prompt']
                    ?? $payload['image']['prompt']
                    ?? $content->inputs['image']['prompt']
                    ?? null;

                $prompt = $imagePrompt ?: $this->buildAutoPrompt($content->title, $content->body_md ?? '', $content->inputs ?? []);
                $bytes  = $images->generate($prompt, '1536x1536');
                $filename = 'ig/' . now()->format('Y/m/') . Str::uuid()->toString() . '.jpg';

                Storage::disk('public')->put($filename, $bytes);
                $publicUrl = asset(Storage::url($filename));
            }

            $client = new InstagramClient($integration->access_token);
            $container = $client->createImageContainer($integration->ig_user_id, $publicUrl, $caption);
            $creationId = $container['id'] ?? null;
            if (!$creationId) {
                throw new \RuntimeException('Kunde inte skapa Instagram-container.');
            }

            $resp = $client->publishContainer($integration->ig_user_id, $creationId);

            $updatePayload = $payload;
            if ($imageAssetId) {
                $updatePayload['image_asset_id'] = (int)$imageAssetId;
                $updatePayload['image_bank_path'] = $asset->path;
            } else {
                $updatePayload['image_used'] = true;
                $updatePayload['prompt'] = $prompt;
                $updatePayload['image_url'] = $publicUrl;
            }

            $pub->update([
                'status'      => 'published',
                'external_id' => $resp['id'] ?? $creationId,
                'payload'     => $updatePayload,
                'message'     => 'OK (image)',
            ]);

            if ($imageAssetId) {
                ImageAsset::markUsed((int)$imageAssetId, $pub->id);
            }

            $usage->increment($customerId, 'ai.publish.instagram');
            $usage->increment($customerId, 'ai.publish');
        } catch (\Throwable $e) {
            $pub->update(['status' => 'failed', 'message' => $e->getMessage()]);
            throw $e;
        }
    }

    private function cleanCaption(string $input): string
    {
        $t = str_replace(["\r\n","\r"], "\n", (string)$input);

        if (str_starts_with(trim($t), '```') && str_ends_with(trim($t), '```')) {
            $t = preg_replace('/^```[a-zA-Z0-9_-]*\n?/','', trim($t));
            $t = preg_replace("/\n?```$/", '', $t);
        }

        $t = preg_replace('/^#{1,6}\s*.+$/m', '', $t);         // rubriker
        $t = preg_replace('/!\[.*?\]\([^)]*\)/s', '', $t);     // MD-bilder
        $t = preg_replace('/<img[^>]*\/?>/is', '', $t);        // HTML-bilder
        $t = preg_replace('/^\s*(Nyckelord|Keywords|Stil|Style|CTA|Målgrupp|Audience|Brand voice)\s*:\s*.*$/im', '', $t);
        // Hashtags kan behållas på IG – rensa om du vill:
        // $t = preg_replace('/^\s*(?:#[\p{L}\p{N}_-]+(?:\s+|$))+$/um', '', $t);
        // $t = preg_replace('/(^|\s)#[\p{L}\p{N}_-]+/u', '$1', $t);
        $t = preg_replace('/^\s*[\*\-]\s+/m', '- ', $t);       // listpunkter → streck
        $t = preg_replace('/\n{3,}/', "\n\n", $t);
        $t = preg_replace('/^[ \t]+|[ \t]+$/m', '', $t);

        return trim($t);
    }

    private function buildAutoPrompt(?string $title, string $body, array $inputs): string
    {
        $kw    = implode(', ', $inputs['keywords'] ?? []);
        $voice = $inputs['brand']['voice'] ?? null;
        $aud   = $inputs['audience'] ?? null;

        return trim("Create a modern square photo suitable for Instagram feed.
Title: {$title}
Keywords: {$kw}
Audience: {$aud}
Brand voice: {$voice}
Style: vibrant, aesthetic, minimal text cues, square composition, photographic.");
    }
}
