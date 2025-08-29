<?php

namespace App\Jobs;

use App\Support\Usage;
use App\Models\KeywordSuggestion;
use App\Models\RankingSnapshot;
use App\Models\Site;
use App\Services\AI\AiProviderManager;
use App\Services\Billing\QuotaGuard;
use App\Services\Sites\IntegrationManager;
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
                $quota->checkOrFail($customer, 'ai.generate');
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
                    'title'   => $snap->keyword,
                    'excerpt' => '',
                    'html'    => '',
                ];
            }

            if (empty($doc['id'])) continue;

            $url = (string)($doc['url'] ?? $site->url);
            $title = trim((string)($doc['title'] ?? ''));
            $excerpt = trim((string)($doc['excerpt'] ?? ''));
            $html = (string)($doc['html'] ?? '');
            $text = Str::of($html)->stripTags()->squish()->limit(4000);

            $rankInfo = $list->map(fn($r) => [
                'keyword'  => $r->keyword,
                'position' => $r->position
            ])->values()->all();

            $prompt = "Du är en svensk SEO-specialist. Professionell ton, inga meta-fraser.\n".
                "Uppgift: föreslå nya relevanta nyckelord (5–10) och optimera meta-title (max 60 tecken) ".
                "samt meta-description (minst 140 teken max 155 tecken).\n".
                "Data:\nTitel: {$title}\nUtdrag: {$excerpt}\nInnehåll (trimmat): {$text}\n".
                "Ranking: ".json_encode($rankInfo, JSON_UNESCAPED_UNICODE)."\n".
                "Returnera ENDAST JSON i detta format:\n".
                "{\n".
                "  \"keywords\": [\"...\"],\n".
                "  \"title\": {\"current\":\"...\",\"suggested\":\"...\"},\n".
                "  \"meta\": {\"current\":\"...\",\"suggested\":\"...\"},\n".
                "  \"insights\": [\"Minst tre korta insikter om varför förslagen förbättrar SEO och användarupplevelse\"]\n".
                "}";

            $out = $prov->generate($prompt, ['max_tokens' => 600, 'temperature' => 0.5]);

            // Försök plocka sista JSON-objektet
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

            $insights = Arr::get($obj, 'insights', []);
            if (empty($insights)) {
                $insights = [
                    "Den föreslagna titeln är tydligare och mer relevant för sökningar.",
                    "Metabeskrivningen är mer säljande och kan öka CTR.",
                    "Nyckelorden är mer specifika och förbättrar synligheten."
                ];
            }

            KeywordSuggestion::updateOrCreate(
                ['site_id' => $site->id, 'wp_post_id' => (int)$pid, 'wp_type' => $ptype],
                [
                    'url' => $url,
                    'current' => [
                        'title'    => $title,
                        'meta'     => $excerpt,
                        'keywords' => [],
                    ],
                    'suggested' => [
                        'title'    => Arr::get($obj, 'title.suggested'),
                        'meta'     => Arr::get($obj, 'meta.suggested'),
                        'keywords' => Arr::get($obj, 'keywords', []),
                    ],
                    'insights' => $insights,
                    'status'   => 'new',
                ]
            );

            $usage->increment($customer->id, 'ai.generate', now()->format('Y-m'), 1);
        }
    }
}
