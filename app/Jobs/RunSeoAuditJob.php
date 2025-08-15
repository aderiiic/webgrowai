<?php

namespace App\Jobs;

use App\Models\{SeoAudit, Site, WpIntegration};
use App\Services\WordPressClient;
use GuzzleHttp\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class RunSeoAuditJob implements ShouldQueue
{
    use Queueable;

    public function __construct(public int $siteId) {}

    private function psiClient(): Client
    {
        return new Client(['timeout' => 60]);
    }

    private function psiEndpoint(): string
    {
        return 'https://www.googleapis.com/pagespeedonline/v5/runPagespeed';
    }

    private function baseQuery(string $url, string $strategy): array
    {
        $key = config('services.pagespeed.key');
        if (!$key) {
            throw new \RuntimeException('PAGESPEED_API_KEY saknas i .env');
        }
        return [
            'url' => $url,
            'key' => $key,
            'strategy' => $strategy, // 'mobile' | 'desktop'
        ];
    }

    // Bygg query-string med upprepade category-parametrar: category=...&category=...
    private function buildQueryString(array $base, array $categories = []): string
    {
        $qs = http_build_query($base, '', '&', PHP_QUERY_RFC3986);
        foreach ($categories as $cat) {
            $qs .= '&category=' . rawurlencode($cat);
        }
        return $qs;
    }

    // Gör ett PSI-anrop med valfria kategorier (korrekt serialiserade)
    private function requestPsiWithCats(string $url, string $strategy, array $categories): array
    {
        $base = $this->baseQuery($url, $strategy);
        $queryString = $this->buildQueryString($base, $categories);
        $fullUrl = $this->psiEndpoint() . '?' . $queryString;

        Log::info('[PSI] Request', ['url' => $fullUrl]);

        $res = $this->psiClient()->get($fullUrl, ['timeout' => 60]);
        return json_decode((string) $res->getBody(), true);
    }

    // Försök: 1) ett anrop för alla; 2) per-kategori för saknade; 3) fallback-strategi per saknad
    private function fetchAllCategoriesMerged(string $url, string $primary, string $fallback): array
    {
        $cats = ['performance','accessibility','best-practices','seo'];

        // 1) Alla på en gång
        $data = $this->requestPsiWithCats($url, $primary, $cats);

        // 2) Komplettera per saknad kategori (primär strategi)
        foreach ($cats as $cat) {
            if (Arr::get($data, "lighthouseResult.categories.$cat.score") === null) {
                $single = $this->requestPsiWithCats($url, $primary, [$cat]);
                $node = Arr::get($single, "lighthouseResult.categories.$cat");
                if ($node !== null) {
                    $data['lighthouseResult']['categories'][$cat] = $node;
                }
            }
        }

        // 3) Fallback för kvarvarande saknade
        foreach ($cats as $cat) {
            if (Arr::get($data, "lighthouseResult.categories.$cat.score") === null) {
                $single = $this->requestPsiWithCats($url, $fallback, [$cat]);
                $node = Arr::get($single, "lighthouseResult.categories.$cat");
                if ($node !== null) {
                    $data['lighthouseResult']['categories'][$cat] = $node;
                }
            }
        }

        // Säkerställ struktur även om categories saknas helt
        if (!isset($data['lighthouseResult']['categories'])) {
            $data['lighthouseResult']['categories'] = [];
        }

        return $data;
    }

    private function score(?array $data, string $cat): ?int
    {
        if (!$data) return null;
        $val = Arr::get($data, "lighthouseResult.categories.$cat.score");
        return $val === null ? null : (int) round(((float) $val) * 100);
    }

    public function handle(): void
    {
        $site = Site::findOrFail($this->siteId);
        Log::info('[SEO Audit] Start (PSI)', ['site_id' => $site->id, 'url' => $site->url]);

        $integration = WpIntegration::where('site_id', $site->id)->first();

        $audit = SeoAudit::create([
            'site_id' => $site->id,
            'title_issues' => 0,
            'meta_issues' => 0,
        ]);

        $titleIssues = 0;
        $metaIssues = 0;

        // 1) WP-analys (titlar/meta)
        if ($integration) {
            try {
                $wp = WordPressClient::for($integration);
                $posts = $wp->getPosts(['per_page' => 20, 'status' => 'any']);
                foreach ($posts as $p) {
                    $postId = $p['id'] ?? null;
                    $postLink = $p['link'] ?? $site->url;

                    $title = trim(strip_tags($p['title']['rendered'] ?? ''));
                    if ($title === '' || Str::length($title) > 60) {
                        $titleIssues++;
                        $audit->items()->create([
                            'type' => 'title',
                            'page_url' => $postLink,
                            'message' => $title === '' ? 'Saknar titel' : 'Titel är för lång (>60 tecken)',
                            'severity' => 'medium',
                            'data' => ['length' => Str::length($title), 'post_id' => $postId],
                        ]);
                    }

                    $meta = trim(strip_tags($p['excerpt']['rendered'] ?? ''));
                    if ($meta === '' || Str::length($meta) > 160) {
                        $metaIssues++;
                        $audit->items()->create([
                            'type' => 'meta',
                            'page_url' => $postLink,
                            'message' => $meta === '' ? 'Saknar meta description' : 'Meta description är för lång (>160 tecken)',
                            'severity' => 'low',
                            'data' => ['length' => Str::length($meta), 'post_id' => $postId],
                        ]);
                    }
                }
            } catch (\Throwable $e) {
                Log::error('[SEO Audit] WP-analys fel', ['site_id' => $site->id, 'error' => $e->getMessage()]);
                $audit->items()->create([
                    'type' => 'title',
                    'page_url' => $site->url,
                    'message' => 'WP-analys misslyckades',
                    'severity' => 'high',
                ]);
            }
        }

        // 2) PSI – korrekt category-serialisering + fallback
        $primary  = config('services.pagespeed.strategy', 'mobile');
        $fallback = $primary === 'mobile' ? 'desktop' : 'mobile';

        $data = $this->fetchAllCategoriesMerged($site->url, $primary, $fallback);

        $perf = $this->score($data, 'performance');
        $acc  = $this->score($data, 'accessibility');
        $bp   = $this->score($data, 'best-practices');
        $seo  = $this->score($data, 'seo');

        // 3) Spara
        $audit->update([
            'lighthouse_performance'    => $perf,
            'lighthouse_accessibility'  => $acc,
            'lighthouse_best_practices' => $bp,
            'lighthouse_seo'            => $seo,
            'title_issues'              => $titleIssues,
            'meta_issues'               => $metaIssues,
            'summary' => [
                'checked_at'        => now()->toIso8601String(),
                'strategy_primary'  => $primary,
                'strategy_fallback' => $fallback,
            ],
        ]);

        try {
            $usage->increment($site->customer_id, 'seo.audit');
        } catch (\Throwable $e) {
            Log::warning('[Usage] increment seo.audit failed', ['site_id' => $site->id, 'error' => $e->getMessage()]);
        }

        Log::info('[SEO Audit] Klar (PSI)', [
            'site_id' => $site->id,
            'perf' => $perf, 'acc' => $acc, 'bp' => $bp, 'seo' => $seo,
            'title_issues' => $titleIssues, 'meta_issues' => $metaIssues,
        ]);
    }
}
