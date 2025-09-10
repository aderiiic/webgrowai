<?php

namespace App\Jobs;

use App\Models\ContentPublication;
use App\Models\SocialIntegration;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class RefreshPublicationMetricsJob implements ShouldQueue
{
    use Queueable, InteractsWithQueue;

    public function __construct(public int $publicationId)
    {
        $this->onQueue('metrics');
        $this->afterCommit();
    }

    public function handle(): void
    {
        $pub = ContentPublication::with('content')->find($this->publicationId);
        if (!$pub) return;

        // Endast publicerade inlägg med external_id
        if ($pub->status !== 'published' || empty($pub->external_id)) {
            Log::info('[Metrics] Skip – not published or no external_id', ['pub_id' => $this->publicationId]);
            return;
        }

        // Förbered resultat
        $metrics = (array) ($pub->metrics ?? []);
        $channel = $pub->target; // 'facebook','instagram','linkedin','wp','shopify','wp'/'wordpress'

        try {
            switch ($channel) {
                case 'facebook':
                case 'fb':
                    $data = $this->fetchFacebook($pub);
                    $metrics = array_merge($metrics, $data);
                    break;

                case 'instagram':
                    $data = $this->fetchInstagram($pub);
                    $metrics = array_merge($metrics, $data);
                    break;

                case 'linkedin':
                    $data = $this->fetchLinkedIn($pub);
                    $metrics = array_merge($metrics, $data);
                    break;

                case 'wp':
                case 'wordpress':
                case 'shopify':
                default:
                    // Enkel placeholder – webben saknar ett enhetligt API här.
                    $data = [
                        'channel'     => $channel,
                        'impressions' => null,
                        'reach'       => null,
                        'reactions'   => null,
                        'likes'       => null,
                        'comments'    => null,
                        'shares'      => null,
                        'updated_at'  => Carbon::now()->toIso8601String(),
                    ];
                    $metrics = array_merge($metrics, $data);
                    break;
            }

            $pub->update([
                'metrics'              => $metrics,
                'metrics_refreshed_at' => now(),
            ]);
        } catch (\Throwable $e) {
            Log::error('[Metrics] Failed', ['pub_id' => $this->publicationId, 'error' => $e->getMessage()]);
            // Låt fel bubbla eller svälj – här sväljer vi för att inte markera jobbet som failed
        }
    }

    private function fetchFacebook(ContentPublication $pub): array
    {
        // external_id kan vara format "PAGEID_POSTID"
        $content = $pub->content;
        $si = SocialIntegration::where('site_id', $content?->site_id)->where('provider', 'facebook')->first();
        if (!$si || empty($si->access_token)) {
            return $this->emptyWithChannel('facebook');
        }

        $postId = $pub->external_id;
        // Minimalt: hämta insikter via Graph v19.0
        $url = "https://graph.facebook.com/v19.0/{$postId}/insights";
        $qs  = http_build_query(['metric' => 'post_impressions,post_impressions_unique,post_reactions_by_type_total,post_engaged_users', 'access_token' => $si->access_token]);

        $resp = $this->httpGetJson("{$url}?{$qs}");
        $map  = $this->fbMap($resp);

        // Bygg extern URL om inte satt (visar på sidan)
        $externalUrl = $pub->external_url;
        if (!$externalUrl) {
            // Om postId är som "PAGEID_POSTID"
            if (strpos($postId, '_') !== false) {
                [$pageId, $purePost] = explode('_', $postId, 2);
                $externalUrl = "https://www.facebook.com/{$pageId}/posts/{$purePost}";
            }
        }

        if ($externalUrl && $externalUrl !== $pub->external_url) {
            $pub->update(['external_url' => $externalUrl]);
        }

        return [
            'channel'     => 'facebook',
            'impressions' => $map['impressions'] ?? null,
            'reach'       => $map['reach'] ?? null,
            'reactions'   => $map['reactions'] ?? null,
            'likes'       => $map['likes'] ?? null,
            'comments'    => $map['comments'] ?? null, // kommentarantal kräver extra anrop; lämnas null här
            'shares'      => $map['shares'] ?? null,   // samma
            'updated_at'  => Carbon::now()->toIso8601String(),
        ];
    }

    private function fbMap(?array $resp): array
    {
        $out = ['impressions' => null, 'reach' => null, 'reactions' => null, 'likes' => null, 'comments' => null, 'shares' => null];
        if (!is_array($resp) || empty($resp['data']) || !is_array($resp['data'])) {
            return $out;
        }
        foreach ($resp['data'] as $row) {
            $name = $row['name'] ?? '';
            $val  = is_array($row['values'][0] ?? null) ? ($row['values'][0]['value'] ?? null) : null;
            if ($name === 'post_impressions') $out['impressions'] = is_numeric($val) ? (int)$val : null;
            if ($name === 'post_impressions_unique') $out['reach'] = is_numeric($val) ? (int)$val : null;
            if ($name === 'post_engaged_users') $out['reactions'] = is_numeric($val) ? (int)$val : null; // approximativt
            if ($name === 'post_reactions_by_type_total' && is_array($val)) {
                $likes = (int)($val['like'] ?? 0);
                $love  = (int)($val['love'] ?? 0);
                $care  = (int)($val['care'] ?? 0);
                $haha  = (int)($val['haha'] ?? 0);
                $wow   = (int)($val['wow'] ?? 0);
                $sad   = (int)($val['sad'] ?? 0);
                $angry = (int)($val['angry'] ?? 0);
                $out['likes']     = $likes;
                $out['reactions'] = ($out['reactions'] ?? 0) + $likes + $love + $care + $haha + $wow + $sad + $angry;
            }
        }
        return $out;
    }

    private function fetchInstagram(ContentPublication $pub): array
    {
        $content = $pub->content;
        $si = SocialIntegration::where('site_id', $content?->site_id)->where('provider', 'instagram')->first();
        if (!$si || empty($si->access_token)) {
            return $this->emptyWithChannel('instagram');
        }

        // IG Basic metrics kräver mediainformation, ofta via /{media-id}/insights
        $mediaId = $pub->external_id;
        $url = "https://graph.facebook.com/v19.0/{$mediaId}/insights";
        $qs  = http_build_query(['metric' => 'impressions,reach,engagement,likes,comments', 'access_token' => $si->access_token]);

        $resp = $this->httpGetJson("{$url}?{$qs}");
        $map  = $this->igMap($resp);

        return [
            'channel'     => 'instagram',
            'impressions' => $map['impressions'] ?? null,
            'reach'       => $map['reach'] ?? null,
            'reactions'   => $map['engagement'] ?? null,
            'likes'       => $map['likes'] ?? null,
            'comments'    => $map['comments'] ?? null,
            'shares'      => null, // IG delar inte alltid ut “shares”
            'updated_at'  => Carbon::now()->toIso8601String(),
        ];
    }

    private function igMap(?array $resp): array
    {
        $out = ['impressions' => null, 'reach' => null, 'engagement' => null, 'likes' => null, 'comments' => null];
        if (!is_array($resp) || empty($resp['data']) || !is_array($resp['data'])) {
            return $out;
        }
        foreach ($resp['data'] as $row) {
            $name = $row['name'] ?? '';
            $val  = is_array($row['values'][0] ?? null) ? ($row['values'][0]['value'] ?? null) : null;
            if (is_numeric($val)) $val = (int)$val;
            $out[$name] = $val;
        }
        return $out;
    }

    private function fetchLinkedIn(ContentPublication $pub): array
    {
        // En del konton har begränsad statistikåtkomst; här sparar vi struktur.
        $out = [
            'channel'     => 'linkedin',
            'impressions' => null,
            'reach'       => null,
            'reactions'   => null,
            'likes'       => null,
            'comments'    => null,
            'shares'      => null,
            'updated_at'  => Carbon::now()->toIso8601String(),
        ];
        return $out;
    }

    private function httpGetJson(string $url, int $timeout = 20): ?array
    {
        try {
            $ctx = stream_context_create(['http' => ['timeout' => $timeout]]);
            $json = @file_get_contents($url, false, $ctx);
            if ($json === false) return null;
            return json_decode($json, true);
        } catch (\Throwable) {
            return null;
        }
    }

    private function emptyWithChannel(string $ch): array
    {
        return [
            'channel'     => $ch,
            'impressions' => null,
            'reach'       => null,
            'reactions'   => null,
            'likes'       => null,
            'comments'    => null,
            'shares'      => null,
            'updated_at'  => Carbon::now()->toIso8601String(),
        ];
    }
}
