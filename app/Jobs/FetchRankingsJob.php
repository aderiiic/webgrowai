<?php

namespace App\Jobs;

use App\Models\RankingSnapshot;
use App\Models\Site;
use App\Models\WpIntegration;
use App\Services\SEO\SerpApiClient;
use App\Services\WordPressClient;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Str;

class FetchRankingsJob implements ShouldQueue
{
    use Queueable;

    public function __construct(public int $siteId, public int $limit = 5)
    {
        $this->onQueue('default');
    }

    public function handle(): void
    {
        $site = Site::findOrFail($this->siteId);
        $integration = WpIntegration::where('site_id', $site->id)->firstOrFail();
        $wp = WordPressClient::for($integration);

        $apiKey = config('services.serpapi.key');
        if (!$apiKey) {
            throw new \RuntimeException('SERPAPI_API_KEY saknas i .env');
        }

        $serp = new SerpApiClient($apiKey);

        $takePages = max(0, $this->limit);
        $pages = $takePages > 0 ? $wp->getPages([
            'per_page' => min($takePages, 100),
            'orderby' => 'modified',
            'order' => 'desc',
            'status' => 'publish',
        ]) : [];

        $remaining = max(0, $this->limit - count($pages));
        $posts = $remaining > 0 ? $wp->getPosts([
            'per_page' => min($remaining, 100),
            'orderby' => 'modified',
            'order' => 'desc',
            'status' => 'publish',
        ]) : [];

        $items = array_merge($pages, $posts);

        $domain = parse_url($site->url, PHP_URL_HOST) ?: $site->url;

        foreach ($items as $it) {
            $pid   = (int)($it['id'] ?? 0);
            $ptype = (string)($it['type'] ?? 'page'); // korrekt typ från WP: 'page' | 'post'
            $url   = (string)($it['link'] ?? $site->url);
            $title = trim(strip_tags($it['title']['rendered'] ?? ''));

            $baseKw = Str::of($title)->lower()->replace(['–','—','|',':','.'], ' ')->squish()->explode(' ');
            $baseKw = $baseKw->filter(fn($w) => Str::length($w) > 2)->take(6)->implode(' ');
            if (!$baseKw) continue;

            $data = $serp->search($baseKw.' site:'.$domain);

            $organic = $data['organic_results'] ?? [];
            $pos = null; $link = null;
            foreach ($organic as $idx => $row) {
                $link = $row['link'] ?? null;
                if ($link && Str::startsWith($link, $url)) {
                    $pos = $row['position'] ?? ($idx + 1);
                    break;
                }
            }

            RankingSnapshot::create([
                'site_id'    => $site->id,
                'wp_post_id' => $pid,
                'wp_type'    => $ptype,
                'url'        => $url,
                'url_hash'   => hash('sha256', $url),
                'keyword'    => $baseKw,
                'position'   => $pos,
                'serp_link'  => $link,
                'device'     => 'desktop',
                'locale'     => 'se',
                'checked_at' => now(),
            ]);
        }
    }
}
