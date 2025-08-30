<?php

namespace App\Jobs;

use App\Models\ContentPublication;
use App\Models\SocialIntegration;
use App\Services\Social\FacebookClient;
use App\Support\Usage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class PublishToFacebookJob implements ShouldQueue
{
    use Queueable, InteractsWithQueue;

    public function __construct(public int $publicationId)
    {
        $this->onQueue('social');
        // Liten fördröjning för att undvika race med transaktioner
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

        // Respektera ev. schemaläggning om jobbet skulle vakna för tidigt
        if ($pub->scheduled_at) {
            $seconds = now()->diffInSeconds($pub->scheduled_at, false);
            if ($seconds > 5) {
                Log::info('[PublishToFacebookJob] För tidigt - releasar tills schematid', [
                    'pub_id' => $pub->id,
                    'delay'  => $seconds,
                ]);
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
            // Hämta Facebook-integration för sajt
            $siteId = $content->site_id;
            $si = SocialIntegration::where('site_id', $siteId)
                ->where('provider', 'facebook')
                ->first();

            if (!$si || empty($si->access_token) || empty($si->page_id)) {
                throw new \RuntimeException('Facebook-integration saknar access token eller page_id');
            }

            $payload = $pub->payload ?? [];
            // Bygg text: använd ev. explicit payload-text, annars AI-innehåll (title + body)
            $raw = $payload['text']
                ?? trim(($content->title ? $content->title . "\n\n" : '') . ($content->body_md ?? ''));
            $message = mb_substr($this->cleanSocialText($raw), 0, 5000);

            $client = new FacebookClient($si->access_token);

            // Vi förlitar oss på Horizon-delay för schemaläggning.
            // När jobbet väl körs, postar vi direkt.
            $resp = $client->createPagePost($si->page_id, $message);

            if (empty($resp) || empty($resp['id'])) {
                throw new \RuntimeException('Facebook API returnerade tomt svar eller saknar id.');
            }

            $pub->update([
                'status'      => 'published',
                'external_id' => $resp['id'],
                'message'     => 'Publicerat till Facebook',
                'payload'     => $payload,
            ]);

            // Mät användning
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

        if (str_starts_with(trim($t), '```') && str_ends_with(trim($t), '```')) {
            $t = preg_replace('/^```[a-zA-Z0-9_-]*\n?/','', trim($t));
            $t = preg_replace("/\n?```$/", '', $t);
        }

        $t = preg_replace('/^#{1,6}\s*.+$/m', '', $t);         // rubriker
        $t = preg_replace('/!\[.*?\]\([^)]*\)/s', '', $t);     // MD-bilder
        $t = preg_replace('/<img[^>]*\/?>/is', '', $t);        // HTML-bilder
        $t = preg_replace('/^\s*(Nyckelord|Keywords|Stil|Style|CTA|Målgrupp|Audience|Brand voice)\s*:\s*.*$/im', '', $t);
        $t = preg_replace('/^\s*(?:#[\p{L}\p{N}_-]+(?:\s+|$))+$/um', '', $t); // hashtag-rad
        $t = preg_replace('/(^|\s)#[\p{L}\p{N}_-]+/u', '$1', $t);             // inline hashtags
        $t = preg_replace('/^\s*[\*\-]\s+/m', '- ', $t);        // listpunkter → streck
        $t = preg_replace('/\n{3,}/', "\n\n", $t);
        $t = preg_replace('/^[ \t]+|[ \t]+$/m', '', $t);

        return trim($t);
    }
}
