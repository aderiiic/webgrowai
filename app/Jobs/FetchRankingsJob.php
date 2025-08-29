<?php

namespace App\Jobs;

use App\Models\RankingSnapshot;
use App\Models\Site;
use App\Services\SEO\SerpApiClient;
use App\Services\Sites\IntegrationManager;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class FetchRankingsJob implements ShouldQueue
{
    use Queueable;

    public function __construct(public int $siteId, public int $limit = 10)
    {
        $this->onQueue('default');
        // max 10 sidor per körning, hårt satt
        $this->limit = max(1, min((int) $this->limit, 10));
    }

    public function handle(IntegrationManager $integrations): void
    {
        $site = Site::findOrFail($this->siteId);

        // Försök hämta integration, men tillåt null
        $client = null;
        try {
            $client = $integrations->forSite($site->id);
        } catch (\Throwable $e) {
            Log::info('[FetchRankings] ingen integration för site', [
                'site_id' => $site->id,
                'error'   => $e->getMessage(),
            ]);
        }

        $apiKey = (string) config('services.serpapi.key');
        if (!$apiKey) {
            throw new \RuntimeException('SERPAPI_API_KEY saknas i .env');
        }
        $serp = new SerpApiClient($apiKey);

        // 1) Försök hämta dokument från integration (om tillgänglig)
        $docs = [];
        if ($client && method_exists($client, 'supports') && $client->supports('list')) {
            try {
                $docs = $client->listDocuments(['limit' => $this->limit]);
            } catch (\Throwable $e) {
                Log::warning('[FetchRankings] listDocuments fel', [
                    'site_id' => $site->id,
                    'error'   => $e->getMessage()
                ]);
            }
        }

        // 2) Upptäck toppsidor från sitemap/robots/homepage (inklusive menylänkar)
        $discovered = $this->discoverTopPages($site->url, $this->limit * 2);

        // 3) Slå ihop resultat
        $all = collect($docs)
            ->map(fn ($it) => [
                'id'    => (int) Arr::get($it, 'id', 0),
                'type'  => (string) Arr::get($it, 'type', 'page'),
                'url'   => (string) Arr::get($it, 'url', $site->url),
                'title' => trim((string) Arr::get($it, 'title', '')),
                'score' => 60,
            ])
            ->concat(
                collect($discovered)->map(function ($it) use ($site) {
                    $url = (string) Arr::get($it, 'url', $site->url);
                    $syntheticId = self::syntheticIdFromUrl($url);
                    return [
                        'id'    => $syntheticId,
                        'type'  => 'url',
                        'url'   => $url,
                        'title' => trim((string) Arr::get($it, 'title', '')),
                        'score' => (int) Arr::get($it, 'score', 0),
                    ];
                })
            )
            ->filter(fn ($row) => filter_var($row['url'] ?? null, FILTER_VALIDATE_URL))
            ->unique('url')
            ->sortByDesc('score')
            ->take($this->limit) // hård limit 10
            ->values();

        $domain = parse_url($site->url, PHP_URL_HOST) ?: $site->url;

        foreach ($all as $it) {
            $pid   = (int)($it['id'] ?? 0);
            $ptype = (string)($it['type'] ?? 'page');
            $url   = (string)($it['url'] ?? $site->url);
            $title = trim((string)($it['title'] ?? ''));

            // Basnyckelord
            $baseKw = Str::of($title)->lower()->replace(['–', '—', '|', ':', '.'], ' ')->squish()->explode(' ');
            $baseKw = $baseKw->filter(fn ($w) => Str::length($w) > 2)->take(6)->implode(' ');
            if (!$baseKw) {
                $slugWords = Str::of(parse_url($url, PHP_URL_PATH) ?: '')
                    ->trim('/')
                    ->replace(['-', '_'], ' ')
                    ->lower()
                    ->squish()
                    ->explode(' ')
                    ->filter(fn ($w) => Str::length($w) > 2)
                    ->take(6)
                    ->implode(' ');
                $baseKw = $slugWords ?: Str::of($domain)->replace(['www.', '.se', '.com', '.eu', '.net'], '')->toString();
            }

            try {
                $query = $baseKw.' site:'.$domain;
                $data = $serp->search($query);
                $organic = $data['organic_results'] ?? [];
                $pos = null;
                $link = null;

                foreach ($organic as $idx => $row) {
                    $linkCandidate = $row['link'] ?? null;
                    if ($linkCandidate && Str::startsWith($linkCandidate, $url)) {
                        $pos = $row['position'] ?? ($idx + 1);
                        $link = $linkCandidate;
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
            } catch (\Throwable $e) {
                Log::warning('[FetchRankings] SERP fel', [
                    'site_id' => $site->id,
                    'url' => $url,
                    'error' => $e->getMessage(),
                ]);
            }
        }
    }

    /**
     * Upptäck relevanta sidor via robots/sitemap/homepage/nav-länkar.
     *
     * @return array<int, array{url:string, title:string, score:int}>
     */
    private function discoverTopPages(string $baseUrl, int $limit = 20): array
    {
        $limit = max(5, min($limit, 100));
        $baseUrl = $this->normalizeBaseUrl($baseUrl);
        $host = parse_url($baseUrl, PHP_URL_HOST) ?: '';
        $scheme = parse_url($baseUrl, PHP_URL_SCHEME) ?: 'https';

        $urls = collect();

        // A) robots.txt -> sitemap
        try {
            $robotsUrl = $scheme.'://'.$host.'/robots.txt';
            $resp = Http::timeout(10)->get($robotsUrl);
            if ($resp->ok()) {
                $sitemaps = collect(preg_split('/\r\n|\r|\n/', (string) $resp->body()))
                    ->map(fn ($l) => trim($l))
                    ->filter(fn ($l) => Str::startsWith(Str::lower($l), 'sitemap:'))
                    ->map(fn ($l) => trim(Str::after($l, ':')))
                    ->filter(fn ($u) => filter_var($u, FILTER_VALIDATE_URL))
                    ->values()
                    ->all();

                foreach ($sitemaps as $sm) {
                    $urls = $urls->concat($this->parseSitemapAny($sm, $host));
                }
            }
        } catch (\Throwable $e) {
            Log::info('[FetchRankings] robots.txt ej tillgänglig', ['base' => $baseUrl, 'error' => $e->getMessage()]);
        }

        // B) fallback sitemap
        if ($urls->count() < 5) {
            $candidates = [
                $scheme.'://'.$host.'/sitemap_index.xml',
                $scheme.'://'.$host.'/sitemap.xml',
            ];
            foreach ($candidates as $sm) {
                $urls = $urls->concat($this->parseSitemapAny($sm, $host));
                if ($urls->count() >= $limit) break;
            }
        }

        // C) Lägg till startsidan
        $urls->push([
            'url' => $scheme.'://'.$host.'/',
            'title' => '',
            'score' => 100,
            'priority' => 1.0,
            'lastmod' => null,
        ]);

        // D) Fallback: extrahera nav/meny-länkar från startsidan
        if ($urls->count() < $limit) {
            try {
                $home = $scheme.'://'.$host.'/';
                $resp = Http::timeout(20)->get($home);
                if ($resp->ok()) {
                    $html = (string) $resp->body();
                    preg_match_all('/<a\s[^>]*href=["\']([^"\']+)["\'][^>]*>(.*?)<\/a>/is', $html, $matches, PREG_SET_ORDER);

                    foreach ($matches as $m) {
                        $href = $this->absolutizeUrl($m[1], $home);
                        if (!$this->isSameHost($href, $host) || !$this->isCrawlable($href)) {
                            continue;
                        }
                        $anchor = trim(strip_tags($m[2] ?? ''));
                        $isMenu = preg_match('/(nav|menu|header|main-navigation)/i', $m[0]);

                        $urls->push([
                            'url' => $href,
                            'title' => $anchor,
                            'score' => $this->scoreUrl($href, $isMenu ? 0.9 : 0.5, null) + ($isMenu ? 40 : 0),
                            'priority' => $isMenu ? 0.9 : 0.5,
                            'lastmod' => null,
                        ]);
                    }
                }
            } catch (\Throwable $e) {
                Log::info('[FetchRankings] kunde inte läsa startsida', ['host' => $host, 'error' => $e->getMessage()]);
            }
        }

        // E) ranka + returnera
        return $urls
            ->filter(fn ($row) => filter_var(Arr::get($row, 'url'), FILTER_VALIDATE_URL))
            ->unique('url')
            ->map(function ($row) {
                $priority = (float) ($row['priority'] ?? 0.5);
                $lastmod  = $row['lastmod'] ?? null;
                $score = $this->scoreUrl($row['url'], $priority, $lastmod);
                return [
                    'url' => $row['url'],
                    'title' => (string) ($row['title'] ?? ''),
                    'score' => $score,
                ];
            })
            ->sortByDesc('score')
            ->take($limit)
            ->values()
            ->all();
    }

    /**
     * Försök tolka både sitemapindex och urlset.
     * @return \Illuminate\Support\Collection<int, array{url:string,title:string,score:int,priority:float,lastmod:?string}>
     */
    private function parseSitemapAny(string $sitemapUrl, string $host)
    {
        try {
            $resp = Http::timeout(12)->get($sitemapUrl);
            if (!$resp->ok()) {
                return collect();
            }
            $xml = (string) $resp->body();

            libxml_use_internal_errors(true);
            $sxe = simplexml_load_string($xml);
            if (!$sxe) {
                return collect();
            }

            $namespaces = $sxe->getDocNamespaces(true);
            $ns = $namespaces[''] ?? null;

            // sitemapindex
            if (isset($sxe->sitemap) || $sxe->getName() === 'sitemapindex') {
                $items = collect();
                foreach ($sxe->sitemap as $sm) {
                    $loc = (string) $sm->loc;
                    if ($loc) {
                        $items = $items->concat($this->parseSitemapAny($loc, $host));
                    }
                }
                return $items;
            }

            // urlset
            if (isset($sxe->url) || $sxe->getName() === 'urlset') {
                $rows = collect();
                foreach ($sxe->url as $u) {
                    $loc = trim((string) $u->loc);
                    if (!$loc || !$this->isSameHost($loc, $host) || !$this->isCrawlable($loc)) {
                        continue;
                    }
                    $priority = (float) trim((string) $u->priority ?: '0.5');
                    $lastmod  = (string) $u->lastmod ?: null;

                    $rows->push([
                        'url' => $loc,
                        'title' => '',
                        'score' => $this->scoreUrl($loc, $priority, $lastmod),
                        'priority' => $priority,
                        'lastmod' => $lastmod,
                    ]);
                }
                return $rows;
            }

            return collect();
        } catch (\Throwable $e) {
            Log::info('[FetchRankings] kunde inte tolka sitemap', ['sitemap' => $sitemapUrl, 'error' => $e->getMessage()]);
            return collect();
        }
    }

    private function isSameHost(string $url, string $host): bool
    {
        $uHost = parse_url($url, PHP_URL_HOST) ?: '';
        return Str::lower(ltrim($uHost, 'www.')) === Str::lower(ltrim($host, 'www.'));
    }

    private function isCrawlable(string $url): bool
    {
        $path = parse_url($url, PHP_URL_PATH) ?: '/';
        if (Str::contains($url, ['?utm_', '&utm_', '#'])) return false;
        // exkludera filer som inte är HTML
        if (preg_match('/\.(pdf|png|jpe?g|gif|webp|svg|zip|rar|7z|css|js|mp4|mp3)$/i', $path)) return false;
        return true;
    }

    private function scoreUrl(string $url, float $priority, ?string $lastmod): int
    {
        $score = 0;

        // Bas på path-längd (kortare = “huvud”-sida)
        $path = parse_url($url, PHP_URL_PATH) ?: '/';
        $depth = max(0, count(array_filter(explode('/', trim($path, '/')))));
        $score += 100 - min($depth, 5) * 12; // max ~100, minus per nivå

        // Priority från sitemap
        $score += (int) round(($priority * 100) / 5); // upp till +20

        // Lastmod fräschhet
        if ($lastmod) {
            try {
                $days = now()->diffInDays(\Carbon\Carbon::parse($lastmod), false);
                // senaste 180 dagar ger bonus, äldre ger mindre
                $fresh = max(0, 180 - abs($days));
                $score += (int) round($fresh / 9); // upp till +20
            } catch (\Throwable) {
                // ignorera parse-fel
            }
        }

        // Vanliga nav-sidor boost
        $navHints = ['/','/produkter','/product','/tjanster','/services','/om','/about','/kontakt','/contact','/blog','/artiklar','/kategorier','/kategori','/categories'];
        foreach ($navHints as $hint) {
            if ($hint === '/' && $path === '/') {
                $score += 25;
            } elseif ($hint !== '/' && Str::startsWith(Str::lower($path), $hint)) {
                $score += 12;
            }
        }

        // Straffa “/tag/”, “/author/”, arkiv mm
        if (Str::contains(Str::lower($path), ['/tag/','/etikett/','/author/','/kategori/','/archive'])) {
            $score -= 15;
        }

        return max(0, min(200, $score));
    }

    private function normalizeBaseUrl(string $url): string
    {
        $u = trim($url);
        if (!Str::startsWith($u, ['http://', 'https://'])) {
            $u = 'https://'.ltrim($u, '/');
        }
        // Ta bort path om det inte är bara “/”
        $host = parse_url($u, PHP_URL_HOST) ?: $u;
        $scheme = parse_url($u, PHP_URL_SCHEME) ?: 'https';
        return $scheme.'://'.$host.'/';
    }

    private function absolutizeUrl(string $href, string $base): string
    {
        $href = trim($href);
        if (!$href) return $base;
        if (Str::startsWith($href, ['http://','https://'])) return $href;
        if (Str::startsWith($href, ['//'])) {
            $scheme = parse_url($base, PHP_URL_SCHEME) ?: 'https';
            return $scheme.':'.$href;
        }
        $baseHost = parse_url($base, PHP_URL_SCHEME).'://'.parse_url($base, PHP_URL_HOST);
        if (Str::startsWith($href, ['/'])) {
            return rtrim($baseHost, '/').$href;
        }
        $basePath = parse_url($base, PHP_URL_PATH) ?: '/';
        $dir = rtrim(Str::beforeLast($basePath, '/'), '/');
        return rtrim($baseHost, '/').$dir.'/'.$href;
    }

    /**
     * Stabilt 64-bitars heltals-ID baserat på URL (för icke-CMS-sidor).
     */
    public static function syntheticIdFromUrl(string $url): int
    {
        $hash = sha1($url);
        // Ta första 15 hex-tecken (~60 bitar) och modda till signed 63-bit range
        $n = (int) (hexdec(substr($hash, 0, 15)) % 0x7FFFFFFFFFFFFFFF);
        return $n > 0 ? $n : abs($n);
    }
}
