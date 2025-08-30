<?php

namespace App\Jobs;

use App\Models\ContentPublication;
use App\Models\ImageAsset;
use App\Models\SocialIntegration;
use App\Services\Social\FacebookClient;
use App\Support\Usage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PublishToFacebookJob implements ShouldQueue
{
    use Queueable, InteractsWithQueue;

    public function __construct(public int $publicationId)
    {
        $this->onQueue('social');
        $this->delay(now()->addSeconds(30));
    }

    public function handle(Usage $usage): void
    {
        Log::info('[PublishToFacebookJob] Start', ['pub_id' => $this->publicationId]);

        $pub = ContentPublication::with('content')->find($this->publicationId);
        if (!$pub) {
            Log::warning('[PublishToFacebookJob] Publication saknas', ['pub_id' => $this->publicationId]);
            return;
        }

        if ($pub->scheduled_at) {
            $seconds = now()->diffInSeconds($pub->scheduled_at, false);
            if ($seconds > 5) {
                Log::info('[PublishToFacebookJob] För tidigt - releasar', ['pub_id' => $pub->id, 'delay' => $seconds]);
                $this->release($seconds);
                return;
            }
        }

        if (!in_array($pub->status, ['queued', 'processing'], true)) {
            Log::info('[PublishToFacebookJob] Hoppar över pga status', ['pub_id' => $pub->id, 'status' => $pub->status]);
            return;
        }

        if ($pub->status === 'queued') {
            $pub->update(['status' => 'processing']);
        }

        $content = $pub->content;
        if (!$content) {
            $pub->update(['status' => 'failed', 'message' => 'Content saknas']);
            Log::error('[PublishToFacebookJob] Content saknas', ['pub_id' => $pub->id]);
            return;
        }

        try {
            $siteId = $content->site_id;
            $si = SocialIntegration::where('site_id', $siteId)
                ->where('provider', 'facebook')
                ->first();

            if (!$si || empty($si->access_token) || empty($si->page_id)) {
                throw new \RuntimeException('Facebook-integration saknar access token eller page_id');
            }

            $payload = $pub->payload ?? [];
            $raw = $payload['text']
                ?? trim(($content->title ? $content->title . "\n\n" : '') . ($content->body_md ?? ''));
            // Behåll hashtags och emojis, rensa bara brus
            $message = mb_substr($this->cleanSocialText($raw), 0, 5000);

            $client = new FacebookClient($si->access_token);

            $imageAssetId = $payload['image_asset_id'] ?? null;
            $resp = null;

            if ($imageAssetId) {
                // Posta som bildinlägg med bildbankens fil + caption
                $asset = ImageAsset::findOrFail((int)$imageAssetId);
                if ((int)$asset->customer_id !== (int)$content->customer_id) {
                    throw new \RuntimeException('Otillåten bild.');
                }
                $bytes = Storage::disk($asset->disk)->get($asset->path);
                $filename = basename($asset->path) ?: (Str::uuid()->toString().'.jpg');

                $resp = $client->createPagePhoto($si->page_id, $bytes, $filename, $message);

                // Uppdatera payload med referens
                $payload['image_asset_id'] = (int)$imageAssetId;
                $payload['image_bank_path'] = $asset->path;
            } else {
                // Textinlägg
                $resp = $client->createPagePost($si->page_id, $message);
            }

            if (empty($resp) || empty($resp['id'])) {
                throw new \RuntimeException('Facebook API returnerade tomt svar eller saknar id.');
            }

            $pub->update([
                'status'      => 'published',
                'external_id' => $resp['id'],
                'message'     => $imageAssetId ? 'Publicerat (photo)' : 'Publicerat (text)',
                'payload'     => $payload,
            ]);

            if ($imageAssetId) {
                // Markera bild använd
                if (method_exists(ImageAsset::class, 'markUsed')) {
                    ImageAsset::markUsed((int)$imageAssetId, $pub->id);
                }
            }

            $usage->increment($content->customer_id, 'ai.publish.facebook');
            $usage->increment($content->customer_id, 'ai.publish');

            Log::info('[PublishToFacebookJob] Klart', ['pub_id' => $pub->id, 'fb_id' => $resp['id']]);
        } catch (\Throwable $e) {
            $pub->update(['status' => 'failed', 'message' => $e->getMessage()]);
            Log::error('[PublishToFacebookJob] Misslyckades', [
                'pub_id' => $pub->id,
                'error'  => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    private function cleanSocialText(string $input): string
    {
        $t = str_replace(["\r\n","\r"], "\n", (string)$input);

        // Ta bort kodblock
        $trim = trim($t);
        if (str_starts_with($trim, '```') && str_ends_with($trim, '```')) {
            $t = preg_replace('/^```[a-zA-Z0-9_-]*\n?/', '', $trim);
            $t = preg_replace("/\n?```$/", '', $t);
        }

        // Rensa rubriker, inbäddade bilder och html-img, metadata-rader
        $t = preg_replace('/^#{1,6}\s*.+$/m', '', $t);
        $t = preg_replace('/!\[.*?\]\([^)]*\)/s', '', $t);
        $t = preg_replace('/<img[^>]*\/?>/is', '', $t);
        $t = preg_replace('/^\s*(Nyckelord|Keywords|Stil|Style|CTA|Målgrupp|Audience|Brand voice)\s*:\s*.*$/im', '', $t);

        // Viktigt: behåll hashtags och emojis – ta inte bort dem
        $t = preg_replace('/^\s*[\*\-]\s+/m', '- ', $t);
        $t = preg_replace('/\n{3,}/', "\n\n", $t);
        $t = preg_replace('/^[ \t]+|[ \t]+$/m', '', $t);

        return trim((string)$t);
    }
}
