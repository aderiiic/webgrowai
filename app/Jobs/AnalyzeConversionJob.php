<?php

namespace App\Jobs;

use App\Models\Integration;
use App\Services\Sites\IntegrationManager;
use App\Support\Usage;
use App\Models\ConversionSuggestion;
use App\Models\Site;
use App\Models\WpIntegration;
use App\Services\AI\AiProviderManager;
use App\Services\Billing\QuotaGuard;
use App\Services\WordPressClient;
use Illuminate\Bus\Queueable;
use GuzzleHttp\Client as HttpClient;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class AnalyzeConversionJob implements ShouldQueue
{
    use Queueable;

    public function __construct(public int $siteId, public int $limit = 6)
    {
        $this->onQueue('default');
    }

    public function handle(AiProviderManager $manager, QuotaGuard $quota, Usage $usage, IntegrationManager $integrations): void
    {
        $site = Site::with('customer')->findOrFail($this->siteId);
        $customer = $site->customer;

        $pages = [];
        $integration = Integration::where('site_id', $site->id)->first();

        // 1) Om integration finns, försök via adapter
        if ($integration) {
            try {
                $client = $integrations->forIntegration($integration);
                if (method_exists($client, 'getPages')) {
                    $pages = $client->getPages(['per_page' => $this->limit, 'orderby' => 'modified']);
                } elseif (method_exists($client, 'listPages')) {
                    $pages = $client->listPages($this->limit);
                }
            } catch (\Throwable $e) {
                Log::info('[AnalyzeConversion] adapter misslyckades, faller tillbaka', [
                    'site_id'  => $site->id,
                    'provider' => $integration->provider ?? 'unknown',
                    'error'    => $e->getMessage(),
                ]);
            }
        } else {
            Log::info('[AnalyzeConversion] ingen integration – använder publik upptäckt (sitemap/crawl)', [
                'site_id' => $site->id,
            ]);
        }

        // 2) Fallback: hitta publika sidor att analysera
        if (empty($pages)) {
            $urls = $this->discoverPublicUrls($site, $this->limit);
            if (empty($urls)) {
                // Som sista utväg – analysera startsidan
                $urls = [$site->url];
            }

            $pages = $this->fetchPublicPages($urls);
        }

        if (empty($pages)) {
            Log::warning('[AnalyzeConversion] inga sidor att analysera', ['site_id' => $site->id]);
            return;
        }

        // 3) Kör AI-analysen per sida
        $prov = $manager->choose(null, 'short');
        $guidelines = "Du är en svensk CRO-specialist. Ge konkreta förbättringar utan meta-kommentarer, inga emojis.";

        $biz = trim($site->aiContextSummary());
        if ($biz !== '') {
            $guidelines .= "\nKontext: {$biz}";
        }

        foreach ($pages as $p) {
            try {
                $quota->checkCreditsOrFail($customer, 50, 'credits');
            } catch (\Throwable $e) {
                Log::warning('[AnalyzeConversion] blocked by quota', [
                    'customer_id' => $customer->id,
                    'site_id'     => $site->id,
                    'page_id'     => Arr::get($p, 'id'),
                    'error'       => $e->getMessage(),
                ]);
                break;
            }

            $pid = (int)($p['id'] ?? 0);
            $url = (string)($p['link'] ?? $p['url'] ?? $site->url);
            $title = trim(strip_tags(Arr::get($p, 'title.rendered', $p['title'] ?? '')));
            $html = (string) (Arr::get($p, 'content.rendered', $p['html'] ?? ''));

            // Försök hämta Yoast-title/desc vid WP-integration för bättre "current" kontext
            $yoastTitle = null;
            $yoastDesc  = null;
            try {
                if (isset($client) && method_exists($client, 'getMeta') && $pid > 0) {
                    $m = $client->getMeta($pid, 'page');
                    $yoastTitle = is_string(Arr::get($m, 'yoast_title')) ? trim($m['yoast_title']) : null;
                    $yoastDesc  = is_string(Arr::get($m, 'yoast_description')) ? trim($m['yoast_description']) : null;
                }
            } catch (\Throwable) {
                // tyst
            }

            $text = Str::of($html)->replace(['<script','</script>','<style','</style>'], ' ')->stripTags()->squish()->limit(4000);

            $prompt = $guidelines."\n\n".
                "Analys av sida (titel + innehåll, utdrag nedan). Du ska föreslå:\n".
                "- Rubrik: förbättrad H1 (max 60 tecken) och ev. underrubrik\n".
                "- CTA: primär knapptext (max 25 tecken) och placering (above fold/sektion)\n".
                "- Formulär: placering och antal fält (maximisera konvertering)\n".
                "- Kort motivering (punktlista)\n\n".
                "URL: {$url}\n".
                "Titel: \"{$title}\"\nInnehåll (trimmat):\n{$text}\n\n".
                "Returnera ENDAST JSON med strukturen:\n".
                "{\n".
                "  \"insights\": [\"...\",\"...\"],\n".
                "  \"title\": {\"current\": \"...\", \"suggested\": \"...\", \"subtitle\": \"\"},\n".
                "  \"cta\": {\"current\": \"...\", \"suggested\": \"...\", \"placement\": \"above_fold|section_2|footer\"},\n".
                "  \"form\": {\"current\": \"...\", \"suggested\": {\"placement\": \"above_fold|sidebar|section_2\", \"fields\": [\"name\",\"email\",\"phone\"]}}\n".
                "}\n";

            $json = $prov->generate($prompt, ['max_tokens' => 700, 'temperature' => 0.5]);
            $json = trim(Str::of($json)->after('{')->beforeLast('}'));
            $json = '{'.$json.'}';

            $data = json_decode($json, true);
            if (!is_array($data)) continue;

            ConversionSuggestion::updateOrCreate(
                ['site_id' => $site->id, 'wp_post_id' => $pid, 'wp_type' => 'page'],
                [
                    'url' => $url,
                    'insights' => Arr::get($data, 'insights', []),
                    'suggestions' => [
                        'title' => Arr::get($data, 'title', []),
                        'cta' => Arr::get($data, 'cta', []),
                        'form' => Arr::get($data, 'form', []),
                    ],
                    'status' => 'new',
                    // Inkludera Yoast i suggestions.context för UI-hjälp (ej visning om tomt)
                    // ... existing code ...
                ]
            );

            try {
                $usage->increment($customer->id, 'ai.generate', now()->format('Y-m'), 1);
                $quota->chargeCredits($customer, 50, 'credits');
            } catch (\Throwable $e) {
                Log::warning('[Usage] increment ai.generate failed', ['site_id' => $site->id, 'error' => $e->getMessage()]);
            }
        }
    }

    /**
     * Försök hitta publika sid-URL:er:
     * - sitemap.xml (inkl. undersitemaps) – särskilt effektivt för Shopify
     * - annars: startsida → extrahera interna länkar
     */
    private function discoverPublicUrls(Site $site, int $limit = 12): array
    {
        $http = new HttpClient(['timeout' => 12]);
        $base = rtrim((string) $site->url, '/');
        $host = parse_url($base, PHP_URL_HOST) ?: '';

        $urls = [];

        // 1) sitemap.xml
        try {
            $sitemapUrl = $base.'/sitemap.xml';
            $res = $http->get($sitemapUrl);
            $xml = (string) $res->getBody();

            // Hämta undersitemaps (t.ex. pages_sitemap.xml, articles_sitemap.xml)
            preg_match_all('#<loc>([^<]+)</loc>#i', $xml, $m);
            $locs = array_map('trim', $m[1] ?? []);
            $sitemaps = [];
            foreach ($locs as $loc) {
                // Om filen verkar vara en undersitemap, hämta den
                if (str_ends_with($loc, '.xml') && count($sitemaps) < 10) {
                    $sitemaps[] = $loc;
                }
            }
            if (empty($sitemaps)) {
                // om huvud-sitemap redan listar URL:er
                $urls = $this->extractUrlsFromSitemapXml($xml, $host, $limit);
            } else {
                foreach ($sitemaps as $sm) {
                    try {
                        $r = $http->get($sm);
                        $urls = array_merge($urls, $this->extractUrlsFromSitemapXml((string)$r->getBody(), $host, $limit - count($urls)));
                        if (count($urls) >= $limit) break;
                    } catch (\Throwable) {
                        // Ignorera enstaka fel
                    }
                }
            }
        } catch (\Throwable) {
            // ignorera, går vidare till crawl
        }

        $urls = array_values(array_unique(array_filter($urls)));
        if (count($urls) >= $limit) {
            return array_slice($urls, 0, $limit);
        }

        // 2) Enkel crawl av startsidan (om vi fortfarande saknar URL:er)
        try {
            $res  = $http->get($base);
            $html = (string) $res->getBody();
            $more = $this->extractInternalLinks($html, $base, $host, $limit - count($urls));
            $urls = array_values(array_unique(array_merge($urls, $more)));
        } catch (\Throwable) {
            // ignore
        }

        if (empty($urls)) {
            $urls[] = $base;
        }

        return array_slice($urls, 0, $limit);
    }

    private function extractUrlsFromSitemapXml(string $xml, string $host, int $max): array
    {
        if ($max <= 0) return [];
        preg_match_all('#<loc>([^<]+)</loc>#i', $xml, $m);
        $locs = array_map('trim', $m[1] ?? []);
        $urls = [];
        foreach ($locs as $loc) {
            $uHost = parse_url($loc, PHP_URL_HOST) ?: '';
            if ($uHost && $host && str_ends_with($uHost, $host)) {
                $urls[] = $loc;
                if (count($urls) >= $max) break;
            }
        }
        return $urls;
    }

    private function extractInternalLinks(string $html, string $base, string $host, int $max): array
    {
        if ($max <= 0) return [];
        $urls = [];
        if (preg_match_all('#<a\s[^>]*href=["\']([^"\']+)#i', $html, $m)) {
            foreach ($m[1] as $href) {
                $href = trim($href);
                if ($href === '' || str_starts_with($href, 'javascript:') || str_starts_with($href, '#')) {
                    continue;
                }
                // Absolut vs relativ
                if (str_starts_with($href, 'http://') || str_starts_with($href, 'https://')) {
                    $uHost = parse_url($href, PHP_URL_HOST) ?: '';
                    if ($uHost && ($uHost === $host || str_ends_with($uHost, '.'.$host))) {
                        $urls[] = rtrim($href, '/');
                    }
                } else {
                    // relativ länk
                    $urls[] = rtrim($base.'/'.ltrim($href, '/'), '/');
                }
                if (count($urls) >= $max) break;
            }
        }
        return $urls;
    }

    /**
     * Hämta HTML och sidtitel för en lista URL:er.
     */
    private function fetchPublicPages(array $urls): array
    {
        $http = new HttpClient(['timeout' => 12]);
        $out  = [];
        $i    = 0;

        foreach ($urls as $u) {
            try {
                $res  = $http->get($u, ['headers' => ['User-Agent' => 'WebbiBot/1.0']]);
                $html = (string) $res->getBody();

                $title = '';
                if (preg_match('/<title[^>]*>(.*?)<\/title>/is', $html, $m)) {
                    $title = Str::of($m[1])->stripTags()->squish()->toString();
                }

                $out[] = [
                    'id'      => $i++,
                    'link'    => $u,
                    'title'   => ['rendered' => $title],
                    'content' => ['rendered' => $html],
                ];
            } catch (\Throwable $e) {
                // hoppa över enstaka fel
                Log::info('[AnalyzeConversion] kunde inte hämta publik sida', ['url' => $u, 'error' => $e->getMessage()]);
            }
        }

        return $out;
    }
}
