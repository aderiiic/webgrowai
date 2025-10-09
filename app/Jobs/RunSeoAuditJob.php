<?php

namespace App\Jobs;

use App\Services\AI\AiProviderManager;
use App\Services\Billing\QuotaGuard;
use App\Support\Usage;
use App\Models\{SeoAudit, SeoAuditItem, Site, WpIntegration};
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

    public function __construct(public int $siteId) {
        $this->onQueue('seo');
    }

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

    private function buildQueryString(array $base, array $categories = []): string
    {
        $qs = http_build_query($base, '', '&', PHP_QUERY_RFC3986);
        foreach ($categories as $cat) {
            $qs .= '&category=' . rawurlencode($cat);
        }
        return $qs;
    }

    private function requestPsiWithCats(string $url, string $strategy, array $categories): array
    {
        $base = $this->baseQuery($url, $strategy);
        $queryString = $this->buildQueryString($base, $categories);
        $fullUrl = $this->psiEndpoint() . '?' . $queryString;

        Log::info('[PSI] Request', ['url' => $fullUrl]);

        $res = $this->psiClient()->get($fullUrl, ['timeout' => 60]);
        return json_decode((string) $res->getBody(), true);
    }

    private function fetchAllCategoriesMerged(string $url, string $primary, string $fallback): array
    {
        $cats = ['performance','accessibility','best-practices','seo'];

        $data = $this->requestPsiWithCats($url, $primary, $cats);

        foreach ($cats as $cat) {
            if (Arr::get($data, "lighthouseResult.categories.$cat.score") === null) {
                $single = $this->requestPsiWithCats($url, $primary, [$cat]);
                $node = Arr::get($single, "lighthouseResult.categories.$cat");
                if ($node !== null) {
                    $data['lighthouseResult']['categories'][$cat] = $node;
                }
            }
        }

        foreach ($cats as $cat) {
            if (Arr::get($data, "lighthouseResult.categories.$cat.score") === null) {
                $single = $this->requestPsiWithCats($url, $fallback, [$cat]);
                $node = Arr::get($single, "lighthouseResult.categories.$cat");
                if ($node !== null) {
                    $data['lighthouseResult']['categories'][$cat] = $node;
                }
            }
        }

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

    public function handle(QuotaGuard $quota, Usage $usage, AiProviderManager $ai): void
    {
        $site = Site::with('customer')->findOrFail($this->siteId);
        $customer = $site->customer;

        try {
            $quota->checkOrFail($customer, 'seo.audit');
        } catch (\Throwable $e) {
            Log::warning('[SEO Audit] blocked by quota', [
                'customer_id' => $customer->id,
                'site_id' => $site->id,
                'error' => $e->getMessage(),
            ]);
            return;
        }

        Log::info('[SEO Audit] Start (PSI)', ['site_id' => $site->id, 'url' => $site->url]);

        $integration = WpIntegration::where('site_id', $site->id)->first();

        $audit = SeoAudit::create([
            'site_id' => $site->id,
            'title_issues' => 0,
            'meta_issues' => 0,
        ]);

        $titleIssues = 0;
        $metaIssues = 0;

        // 1) WP-analys (titlar/meta + Yoast-title/description om finns)
        if ($integration) {
            try {
                $wp = WordPressClient::for($integration);
                $posts = $wp->getPosts(['per_page' => 20, 'status' => 'any']);
                foreach ($posts as $p) {
                    $postId = $p['id'] ?? null;
                    $postLink = $p['link'] ?? $site->url;

                    $title = trim(strip_tags($p['title']['rendered'] ?? ''));
                    $meta  = trim(strip_tags($p['excerpt']['rendered'] ?? ''));

                    $yoastTitle = null;
                    $yoastDesc  = null;
                    try {
                        if (method_exists($wp, 'getMeta')) {
                            $m = $wp->getMeta((int)$postId, 'post');
                            $yoastTitle = is_string(Arr::get($m, 'yoast_title')) ? trim($m['yoast_title']) : null;
                            $yoastDesc  = is_string(Arr::get($m, 'yoast_description')) ? trim($m['yoast_description']) : null;
                        }
                    } catch (\Throwable) {
                        // tyst
                    }

                    if ($title === '' || Str::length($title) > 60) {
                        $titleIssues++;
                        $audit->items()->create([
                            'type' => 'title',
                            'page_url' => $postLink,
                            'message' => $title === '' ? 'Saknar titel' : 'Titel är för lång (>60 tecken)',
                            'severity' => 'medium',
                            'data' => [
                                'length' => Str::length($title),
                                'post_id' => $postId,
                                'yoast_title' => $yoastTitle,
                                'yoast_description' => $yoastDesc,
                                'url' => $postLink,
                            ],
                        ]);
                    }

                    if ($meta === '' || Str::length($meta) > 160) {
                        $metaIssues++;
                        $audit->items()->create([
                            'type' => 'meta',
                            'page_url' => $postLink,
                            'message' => $meta === '' ? 'Saknar meta description' : 'Meta description är för lång (>160 tecken)',
                            'severity' => 'low',
                            'data' => [
                                'length' => Str::length($meta),
                                'post_id' => $postId,
                                'yoast_title' => $yoastTitle,
                                'yoast_description' => $yoastDesc,
                                'url' => $postLink,
                            ],
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
                    'data' => ['url' => $site->url],
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

        // 2b) Extrahera Lighthouse-fynd som audit‑items
        try {
            $lhr = Arr::get($data, 'lighthouseResult', []);
            $audits = Arr::get($lhr, 'audits', []);
            $catScores = [
                'performance'     => $perf,
                'accessibility'   => $acc,
                'best-practices'  => $bp,
                'seo'             => $seo,
            ];

            // Hjälptabell: audit-id => kategori (enkel mappning)
            $auditToCat = function (string $id) use ($lhr): ?string {
                // försök via categories.*.auditRefs
                foreach (['performance','accessibility','best-practices','seo'] as $cat) {
                    $refs = Arr::get($lhr, "categories.$cat.auditRefs", []);
                    foreach ($refs as $ref) {
                        if (($ref['id'] ?? null) === $id) return $cat;
                    }
                }
                return null;
            };

            foreach ($audits as $id => $a) {
                $score = Arr::get($a, 'score');
                $scoreDisplayMode = Arr::get($a, 'scoreDisplayMode');
                $title = (string) Arr::get($a, 'title', ucfirst(str_replace('-', ' ', (string)$id)));
                $desc  = trim((string) strip_tags(Arr::get($a, 'description', '')));
                $display = (string) Arr::get($a, 'displayValue', '');
                $details = Arr::get($a, 'details', []);
                $cat = $auditToCat((string)$id) ?? 'lighthouse';

                // Vi tar bara med audits som påverkar (inte 'notApplicable' eller 'informative' utan data)
                if (in_array($scoreDisplayMode, ['notApplicable','manual'], true)) {
                    continue;
                }

                // Sev: låg om score ~1.0, annars medium/hög
                $sev = 'low';
                if (is_numeric($score)) {
                    $s = (float)$score;
                    if ($s < 0.6) $sev = 'high';
                    elseif ($s < 0.9) $sev = 'medium';
                    else $sev = 'low';
                } else {
                    // saknar score men har displayValue => notice
                    if ($display !== '') $sev = 'notice';
                }

                // Försök hitta sid-URL i detaljer (om finns)
                $pageUrl = Arr::get($lhr, 'requestedUrl') ?: $site->url;

                // Opportunity text om relevant
                $opportunity = null;
                if (is_array($details) && ($details['type'] ?? '') === 'opportunity') {
                    $opportunity = Arr::get($details, 'overallSavingsMs');
                    if (is_numeric($opportunity)) {
                        $opportunity = round(((float)$opportunity) / 1000, 1).'s sparpotential';
                    }
                }

                // Skapa item
                $audit->items()->create([
                    'type'     => in_array($cat, ['performance','accessibility','best-practices','seo'], true) ? $cat : 'lighthouse',
                    'page_url' => $pageUrl,
                    'title'    => $title,
                    'message'  => $display ?: $desc,
                    'severity' => $sev,
                    'data'     => [
                        'lh_category'    => $cat,
                        'lh_rule'        => $id,
                        'lh_score'       => $score,
                        'lh_mode'        => $scoreDisplayMode,
                        'lh_display'     => $display,
                        'lh_description' => $desc,
                        'lh_opportunity' => $opportunity,
                        'url'            => $pageUrl,
                    ],
                ]);
            }
        } catch (\Throwable $e) {
            Log::warning('[SEO Audit] Kunde inte extrahera Lighthouse audits', [
                'site_id' => $site->id,
                'error'   => $e->getMessage(),
            ]);
        }

        // 3) Spara huvudresultat
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
            $lastWithAi = SeoAudit::where('site_id', $site->id)
                ->whereNotNull('ai_analysis_generated_at')
                ->latest('ai_analysis_generated_at')
                ->first();

            $shouldGenerateAi = !$lastWithAi || $lastWithAi->ai_analysis_generated_at->lt(now()->subDays(14));

            if ($shouldGenerateAi) {
                $prompt = $this->buildAiPrompt($site, $audit);
                // Tvinga Anthropic om din manager har stöd, annars välj baserat på manager->choose(null, 'long')
                $provider = $ai->choose(null, 'long');

                $raw = $provider->generate($prompt, [
                    'temperature' => 0.4,
                    'max_tokens'  => 1200,
                ]);

                $text = trim((string) $raw);
                if (str_starts_with($text, '```')) {
                    $text = preg_replace('/^```[a-zA-Z0-9_-]*\n?/', '', $text);
                    $text = preg_replace("/\n?```$/", '', $text);
                    $text = trim((string) $text);
                }

                // Begränsa längd (ca 2000 tecken)
                if (mb_strlen($text) > 2000) {
                    $text = mb_substr($text, 0, 2000) . '…';
                }

                $audit->update([
                    'ai_analysis' => $text,
                    'ai_analysis_generated_at' => now(),
                ]);
            }
        } catch (\Throwable $e) {
            Log::warning('[SEO Audit] AI analysis failed', [
                'site_id' => $site->id,
                'audit_id' => $audit->id,
                'error' => $e->getMessage(),
            ]);
        }

        try {
            $usage->increment($customer->id, 'seo.audit', now()->format('Y-m'), 1);
        } catch (\Throwable $e) {
            Log::warning('[Usage] increment seo.audit failed', ['site_id' => $site->id, 'error' => $e->getMessage()]);
        }

        Log::info('[SEO Audit] Klar (PSI)', [
            'site_id' => $site->id,
            'perf' => $perf, 'acc' => $acc, 'bp' => $bp, 'seo' => $seo,
            'title_issues' => $titleIssues, 'meta_issues' => $metaIssues,
            'ai' => (bool) $audit->ai_analysis,
        ]);
    }

    private function buildAiPrompt(\App\Models\Site $site, \App\Models\SeoAudit $audit): string
    {
        $lang = $site->preferredLanguage(); // sv|en|de
        $scores = [
            'Performance' => $audit->lighthouse_performance,
            'Accessibility' => $audit->lighthouse_accessibility,
            'Best Practices' => $audit->lighthouse_best_practices,
            'SEO' => $audit->lighthouse_seo,
        ];

        $scoreLines = [];
        foreach ($scores as $k => $v) {
            $scoreLines[] = "$k: " . ($v === null ? '—' : $v);
        }

        $issues = [
            "Titelproblem: {$audit->title_issues}",
            "Meta‑problem: {$audit->meta_issues}",
        ];

        $ctx = $site->aiContextSummary();
        $url = trim((string) $site->url);

        // Hämta startsidans HTML snabbt (timeout kort) och extrahera lättläst text + viktiga meta
        $pageContext = '';
        try {
            $resp = \Illuminate\Support\Facades\Http::timeout(8)->get($url);
            if ($resp->successful()) {
                $html = (string) $resp->body();
                // Plocka title
                $title = '';
                if (preg_match('/<title[^>]*>(.*?)<\/title>/is', $html, $m)) {
                    $title = \Illuminate\Support\Str::of($m[1])->stripTags()->squish();
                }
                // Plocka meta description
                $metaDesc = '';
                if (preg_match('/<meta[^>]+name=["\']description["\'][^>]*content=["\']([^"\']+)["\']/i', $html, $m)) {
                    $metaDesc = trim($m[1]);
                } elseif (preg_match('/<meta[^>]+property=["\']og:description["\'][^>]*content=["\']([^"\']+)["\']/i', $html, $m)) {
                    $metaDesc = trim($m[1]);
                }
                // Rensa scripts/styles och extrahera synlig text (h1–h3, p)
                $clean = preg_replace('/<script[\s\S]*?<\/script>/i', ' ', $html);
                $clean = preg_replace('/<style[\s\S]*?<\/style>/i', ' ', $clean);
                $clean = preg_replace('/<(\/?h[1-3]|\/?p|br\s*\/?)>/i', "\n", $clean);
                $clean = strip_tags($clean);
                $clean = \Illuminate\Support\Str::of($clean)->replace(["\r"], '')->squish();
                $textSnippet = \Illuminate\Support\Str::limit((string) $clean, 2000, '...');

                $chunks = [];
                if ($title) $chunks[] = "TITLE: {$title}";
                if ($metaDesc) $chunks[] = "META: {$metaDesc}";
                if ($textSnippet) $chunks[] = "TEXT: {$textSnippet}";
                if ($chunks) {
                    $host = parse_url($url, PHP_URL_HOST) ?: $url;
                    $pageContext = "UTDRAG FRÅN STARTSIDAN ({$host}):\n- " . implode("\n- ", $chunks);
                }
            }
        } catch (\Throwable $e) {
            // tyst fail – vi kör vidare utan sidkontext
        }

        // Lokala rubriker/texter
        $head = match ($lang) {
            'en' => "You are an SEO expert. Give a concise, balanced analysis (what’s good and what to improve). Then provide a prioritized Top‑10 action list with the biggest impact. Keep it under ~2000 characters. Use short paragraphs and bullet lists.",
            'de' => "Du bist SEO‑Experte. Gib eine kurze, ausgewogene Analyse (Stärken und Verbesserungen). Danach eine priorisierte Top‑10‑Maßnahmenliste mit größtem Impact. Max. ~2000 Zeichen. Kurze Absätze und Stichpunkte.",
            default => "Du är SEO‑expert. Ge en kort, balanserad analys (vad som är bra och vad som kan förbättras). Ge sedan en prioriterad Topp‑10‑lista över åtgärder med störst effekt. Håll dig under ~2000 tecken. Använd korta stycken och punktlistor.",
        };

        $labelScores = match ($lang) {
            'en' => 'Scores',
            'de' => 'Werte',
            default => 'Poäng',
        };
        $labelIssues = match ($lang) {
            'en' => 'Detected issues',
            'de' => 'Erkannte Probleme',
            default => 'Upptäckta problem',
        };
        $labelFocus = match ($lang) {
            'en' => 'Deliver exactly 10 prioritized actions (Top‑10), each as a short bullet with why it matters and intended effect.',
            'de' => 'Liefere genau 10 priorisierte Maßnahmen (Top‑10), jeweils als kurzer Stichpunkt mit Begründung und erwarteter Wirkung.',
            default => 'Leverera exakt 10 prioriterade åtgärder (Topp‑10), varje som en kort punkt med varför det är viktigt och förväntad effekt.',
        };

        return trim("
            {$head}

            URL: {$url}
            KONTEXT: {$ctx}

            {$labelScores}:
            - " . implode("\n- ", $scoreLines) . "

            {$labelIssues}:
            - " . implode("\n- ", $issues) . "

            " . ($pageContext !== '' ? "{$pageContext}\n\n" : "") . "{$labelFocus}
        ");
    }
}
