<?php

namespace App\Jobs;

use App\Support\Usage;
use App\Models\KeywordSuggestion;
use App\Models\RankingSnapshot;
use App\Models\Site;
use App\Services\AI\AiProviderManager;
use App\Services\Billing\QuotaGuard;
use App\Services\Sites\IntegrationManager;
use App\Services\WordPressClient;
use App\Models\WpIntegration;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class AnalyzeKeywordsJob implements ShouldQueue
{
    use Queueable;

    public function __construct(public int $siteId, public int $limit = 10)
    {
        $this->onQueue('ai');
        $this->limit = max(1, min((int)$this->limit, 50));
    }

    public function handle(IntegrationManager $integrations, AiProviderManager $manager, QuotaGuard $quota, Usage $usage): void
    {
        $site = Site::with('customer')->findOrFail($this->siteId);
        $customer = $site->customer;

        // Hämta integration om möjligt
        $client = null;
        try {
            $client = $integrations->forSite($site->id);
        } catch (\Throwable $e) {
            Log::info('[AnalyzeKeywords] ingen integration för site', ['site_id' => $site->id]);
        }

        $prov = $manager->choose(null, 'short');

        // Hämta snapshots, sortera först efter score (huvudsidor först), sen senaste
        $snapshots = RankingSnapshot::where('site_id', $site->id)
            ->latest('checked_at')
            ->get()
            ->groupBy('wp_post_id')
            ->take($this->limit);

        if ($snapshots->isEmpty()) {
            Log::info('[AnalyzeKeywords] inga ranking-snapshots hittades', ['site_id' => $site->id]);
            return;
        }

        foreach ($snapshots as $pid => $list) {

            try {
                $quota->checkCreditsOrFail($customer, 50, 'credits');
            } catch (\Throwable $e) {
                Log::warning('[AnalyzeKeywords] blocked by quota', [
                    'customer_id' => $customer->id,
                    'site_id'     => $site->id,
                    'post_id'     => $pid,
                    'error'       => $e->getMessage()
                ]);
                break;
            }

            $ptype = Arr::get($list->first(), 'wp_type', 'page');

            // Hämta dokument från integration eller fallback till snapshot
            $doc = null;
            if ($client) {
                try {
                    $doc = $client->getDocument((int)$pid, $ptype);
                } catch (\Throwable $e) {
                    Log::warning('[AnalyzeKeywords] kunde inte hämta dokument', [
                        'site_id' => $site->id,
                        'post_id' => (int)$pid,
                        'type'    => $ptype,
                        'error'   => $e->getMessage()
                    ]);
                }
            }

            if (!$doc) {
                $snap = $list->first();
                $doc = [
                    'id'      => $pid,
                    'url'     => $snap->url,
                    // ... existing code ...
                    'title'   => $snap->title ?? ($snap->ttle ?? ''),
                    'excerpt' => $snap->meta_description ?? '',
                    'html'    => '',
                ];
            }

            if (empty($doc['id'])) continue;

            $url = (string)($doc['url'] ?? $site->url);
            $path = parse_url($url, PHP_URL_PATH) ?: '/';
            $titleRaw = (string)($doc['title'] ?? '');
            $title = $this->normalizeTrivialTitle($titleRaw, $path);
            $excerptRaw = (string)($doc['excerpt'] ?? '');
            $html = (string)($doc['html'] ?? '');
            $text = Str::of($html)->replace(['<script','</script>','<style','</style>'], ' ')->stripTags()->squish()->limit(4000)->toString();
            $biz = trim($site->aiContextSummary());

            // Yoast: försök via flera källor (integrationens meta + WP-klient som i audit-jobbet)
            [$yoastTitle, $yoastDesc] = $this->resolveYoastMeta($client, $site, (int)$pid, $ptype);

            // Sid-unik kontext
            $h1 = $this->extractH1($html) ?: $title;
            $ekws = $site->effectiveKeywords();
            $focus = $this->pickFocusKeyword($h1.' '.$title.' '.$text, $ekws);

            $topicHint = Str::of(($site->name ?? '').' '.$h1.' '.$title.' '.$excerptRaw)
                ->replaceMatches('/[^0-9A-Za-zÅÄÖåäö\s\-]/u', ' ')
                ->squish()
                ->limit(160, '')
                ->toString();

            $rankInfo = $list->map(fn($r) => [
                'keyword'  => $r->keyword,
                'position' => $r->position
            ])->values()->all();

            $seed = hexdec(substr(sha1($url), 0, 6)) % 1000;

            $dataBlock =
                "URL: {$url}\n".
                "Path: {$path}\n".
                "H1: {$h1}\n".
                "Titel (WP): {$title}\n".
                "Meta/utdrag (WP): ".trim($excerptRaw)."\n".
                ($yoastTitle ? "Yoast Title: {$yoastTitle}\n" : "").
                ($yoastDesc ? "Yoast Description: {$yoastDesc}\n" : "").
                "Ranking: ".json_encode($rankInfo, JSON_UNESCAPED_UNICODE)."\n".
                ($focus ? "Fokusnyckelord (härlett): {$focus}\n" : "");

            $prompt =
                "Du är en svensk SEO-specialist. Skriv utan fluff eller meta-kommentarer.\n" .
                ($biz ? "Kontext: {$biz}\n" : "") .
                "Mål: föreslå 6–10 specifika nyckelord för JUST DEN HÄR SIDAN. " .
                "Optimera meta-title (<=60 tecken) och meta-description (140–155 tecken). " .
                "Om Yoast-meta finns, förbättra dem – ignorera inte.\n" .
                "Undvik generiska fraser; gör titeln unik för sidans ämne.\n" .
                "Seed: {$seed}\n" .
                "Data:\n{$dataBlock}\n" .
                "Returnera ENDAST JSON:\n" .
                "{ \"keywords\": [\"...\"], \"title\": {\"current\":\"...\",\"suggested\":\"...\"}, \"meta\": {\"current\":\"...\",\"suggested\":\"...\"}, \"insights\": [\"...\"] }";

            $out = $prov->generate($prompt, ['max_tokens' => 650, 'temperature' => 0.4]);

            // Plocka sista JSON-objektet
            $matches = [];
            preg_match_all('/\{(?:[^{}]|(?R))*\}/s', $out, $matches);
            $jsonCandidate = end($matches[0]) ?: null;
            $obj = json_decode($jsonCandidate, true);

            if (!is_array($obj)) {
                Log::info('[AnalyzeKeywords] ogiltig JSON från AI', [
                    'site_id' => $site->id,
                    'post_id' => (int)$pid,
                    'output'  => $out,
                ]);
                continue;
            }

            // Förslag
            $suggestedTitle = trim((string) Arr::get($obj, 'title.suggested', ''));
            $suggestedMeta  = trim((string) Arr::get($obj, 'meta.suggested', ''));

            // Sanera trivialt “Sida” i både current & suggested
            $currentTitleForObj = $this->sanitizeTitleForCurrent($title);
            $currentMetaForObj  = $this->sanitizeMetaForCurrent($excerptRaw, $h1, $focus);

            if ($this->looksGeneric($suggestedTitle, $h1)) {
                $suggestedTitle = $this->fallbackTitle($h1, $focus, $path, $site->name ?? null);
            }
            if ($this->looksGenericMeta($suggestedMeta, $h1)) {
                $suggestedMeta = $this->fallbackMeta($h1, $focus, $text);
            }

            $suggestedKeywords = Arr::get($obj, 'keywords', []);
            if (!is_array($suggestedKeywords)) $suggestedKeywords = [];
            $topicLower = mb_strtolower($topicHint, 'UTF-8');
            $suggestedKeywords = array_values(array_filter($suggestedKeywords, function ($k) use ($topicLower) {
                $kLower = mb_strtolower((string)$k, 'UTF-8');
                if ($kLower === '' || Str::length($kLower) < 3) return false;
                if (str_contains($topicLower, 'läx') && str_starts_with($kLower, 'lax')) return false;
                return true;
            }));

            $insights = Arr::get($obj, 'insights', []);
            if (empty($insights) || !is_array($insights)) {
                $insights = [
                    "Titeln utgår från sidans H1/ämne för högre relevans.",
                    "Metabeskrivningen sammanfattar värdet tydligt och bör öka CTR.",
                    "Nyckelorden är specifika för denna sida, inte hela sajten."
                ];
            }

            KeywordSuggestion::updateOrCreate(
                ['site_id' => $site->id, 'wp_post_id' => (int)$pid, 'wp_type' => $ptype],
                [
                    'url' => $url,
                    'current' => [
                        'title'    => $currentTitleForObj,
                        'meta'     => $currentMetaForObj,
                        'keywords' => [],
                        'yoast_title'       => $yoastTitle,
                        'yoast_description' => $yoastDesc,
                        'h1'                 => $h1,
                        'path'               => $path,
                        'focus_keyword_hint' => $focus,
                    ],
                    'suggested' => [
                        'title'    => $suggestedTitle,
                        'meta'     => $suggestedMeta,
                        'keywords' => $suggestedKeywords,
                    ],
                    'insights' => $insights,
                    'status'   => 'new',
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

    private function resolveYoastMeta($client, Site $site, int $postId, string $ptype): array
    {
        $title = null; $desc = null;

        // 1) Integrationens meta (olika nycklar eller meta-array)
        if ($client && method_exists($client, 'getMeta')) {
            try {
                $meta = $client->getMeta($postId, $ptype);
                $title = $this->scanMetaForTitle($meta);
                $desc  = $this->scanMetaForDesc($meta);
            } catch (\Throwable) {
                // ignore
            }
        }

        // 2) Fallback via WordPressClient, likt audit-jobbet
        if ((!$title && !$desc) && class_exists(WpIntegration::class) && class_exists(WordPressClient::class)) {
            try {
                $wpInt = WpIntegration::where('site_id', $site->id)->first();
                if ($wpInt) {
                    $wp = WordPressClient::for($wpInt);
                    if (method_exists($wp, 'getMeta')) {
                        $m = $wp->getMeta($postId, $ptype === 'url' ? 'post' : $ptype);
                        $title = $title ?: $this->scanMetaForTitle($m);
                        $desc  = $desc  ?: $this->scanMetaForDesc($m);
                    }
                }
            } catch (\Throwable) {
                // ignore
            }
        }

        return [$title ?: null, $desc ?: null];
    }

    private function scanMetaForTitle(?array $meta): ?string
    {
        if (!is_array($meta)) return null;
        $candidates = [
            'yoast_title','_yoast_wpseo_title','seo_title','rankmath_title',
            'meta._yoast_wpseo_title','_meta._yoast_wpseo_title'
        ];
        foreach ($candidates as $k) {
            $v = Arr::get($meta, $k);
            if (is_string($v) && ($v = trim($v)) !== '') return $v;
        }
        return null;
    }

    private function scanMetaForDesc(?array $meta): ?string
    {
        if (!is_array($meta)) return null;
        $candidates = [
            'yoast_description','_yoast_wpseo_metadesc','seo_description','rankmath_description',
            'meta._yoast_wpseo_metadesc','_meta._yoast_wpseo_metadesc'
        ];
        foreach ($candidates as $k) {
            $v = Arr::get($meta, $k);
            if (is_string($v) && ($v = trim($v)) !== '') return $v;
        }
        return null;
    }

    private function extractH1(string $html): ?string
    {
        if ($html === '') return null;
        if (preg_match('/<h1[^>]*>(.*?)<\/h1>/is', $html, $m)) {
            $h1 = Str::of($m[1] ?? '')->stripTags()->squish()->limit(120)->toString();
            return $h1 ?: null;
        }
        return null;
    }

    private function pickFocusKeyword(string $text, array $candidates): ?string
    {
        if (empty($candidates)) return null;
        $tl = mb_strtolower($text, 'UTF-8');
        $best = null; $bestLen = 0;
        foreach ($candidates as $kw) {
            $k = trim((string)$kw);
            if ($k === '') continue;
            $kl = mb_strtolower($k, 'UTF-8');
            if (mb_strpos($tl, $kl, 0, 'UTF-8') !== false && mb_strlen($kl, 'UTF-8') > $bestLen) {
                $best = $k; $bestLen = mb_strlen($kl, 'UTF-8');
            }
        }
        return $best;
    }

    private function looksGeneric(string $s, ?string $h1): bool
    {
        $l = mb_strtolower($s, 'UTF-8');
        if ($l === '' || $this->isTrivialTitle($s) || ($h1 && $l === mb_strtolower($h1, 'UTF-8'))) return true;
        $genericBits = ['professionella webbsidor', 'mobilappar', 'din digitala närvaro', 'skräddarsydda lösningar', 'vi hjälper', 'vi erbjuder'];
        foreach ($genericBits as $g) if (str_contains($l, $g)) return true;
        return false;
    }

    private function looksGenericMeta(string $s, ?string $h1): bool
    {
        $l = mb_strtolower($s, 'UTF-8');
        if ($l === '' || $this->isTrivialTitle($s)) return true;
        if ($h1 && str_contains($l, mb_strtolower($h1, 'UTF-8')) && mb_strlen($l, 'UTF-8') < 60) return true;
        return false;
    }

    private function fallbackTitle(?string $h1, ?string $focus, string $path, ?string $siteName): string
    {
        $base = $this->normalizeTrivialTitle($h1 ?: ($focus ?: $this->titleFromPath($path)), $path);
        $suffix = $siteName ? ' | '.$siteName : '';
        return Str::of($base)->squish()->limit(60)->toString().$suffix;
    }

    private function fallbackMeta(?string $h1, ?string $focus, string $text): string
    {
        $subject = $this->normalizeTrivialTitle($h1 ?: ($focus ?: null), '/');
        if (!$subject) {
            // plocka första meningsliknande bit ur text
            $snippet = Str::of($text)->limit(155, '')->toString();
            if (preg_match('/^(.{40,155}?)[\.\!\?]\s/u', $text, $mm)) {
                $snippet = $mm[1].'.';
            }
            return $this->trimMeta($snippet);
        }
        $meta = "{$subject}: vad du får och varför det spelar roll. Kontakta oss för nästa steg.";
        return $this->trimMeta($meta);
    }

    private function trimMeta(string $meta): string
    {
        $meta = Str::of($meta)->squish()->toString();
        if (Str::length($meta) <= 155) return $meta;
        $cut = Str::substr($meta, 0, 155);
        $lastSpace = strrpos($cut, ' ');
        if ($lastSpace !== false) $cut = substr($cut, 0, $lastSpace);
        return rtrim($cut, ' .,:;').'...';
    }

    private function normalizeTrivialTitle(?string $t, string $path): string
    {
        $t = trim((string)$t);
        if ($this->isTrivialTitle($t)) {
            $fromPath = $this->titleFromPath($path);
            return $fromPath ?: 'Sida';
        }
        return $t;
    }

    private function sanitizeTitleForCurrent(string $t): string
    {
        return $this->isTrivialTitle($t) ? '' : $t;
    }

    private function sanitizeMetaForCurrent(string $excerpt, ?string $h1, ?string $focus): string
    {
        $e = trim($excerpt);
        if ($this->isTrivialTitle($e) || $e === '') {
            return ''; // låt UI visa Yoast om finns, annars '—'
        }
        return $e;
    }

    private function isTrivialTitle(?string $t): bool
    {
        $l = mb_strtolower(trim((string)$t), 'UTF-8');
        return $l === '' || in_array($l, ['sida','page','home','startsida','untitled'], true);
    }

    private function titleFromPath(string $path): ?string
    {
        $slug = Str::of($path)->trim('/')->replace(['-','_'],' ')->squish()->title()->toString();
        return $slug !== '' ? $slug : null;
    }
}
