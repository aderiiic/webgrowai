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

    public function __construct(public int $siteId)
    {
        $this->onQueue('ai');
    }

    public function handle(IntegrationManager $integrations, AiProviderManager $manager, QuotaGuard $quota, Usage $usage): void
    {
        $site = Site::with('customer')->findOrFail($this->siteId);
        $customer = $site->customer;

        $client = $integrations->forSite($site->id);
        $prov = $manager->choose(null, 'short');

        $rankings = RankingSnapshot::where('site_id', $site->id)
            ->latest('checked_at')->get()->groupBy('wp_post_id');

        if ($rankings->isEmpty()) {
            Log::info('[AnalyzeKeywords] inga ranking-snapshots hittades', ['site_id' => $site->id]);
            return;
        }

        foreach ($rankings as $pid => $list) {
            try {
                $quota->checkOrFail($customer, 'ai.generate');
            } catch (\Throwable $e) {
                Log::warning('[AnalyzeKeywords] blocked by quota', ['customer_id' => $customer->id, 'site_id' => $site->id, 'post_id' => $pid, 'error' => $e->getMessage()]);
                break;
            }

            $ptype = Arr::get($list->first(), 'wp_type', 'page');

            try {
                $doc = $client->getDocument((int)$pid, $ptype);
            } catch (\Throwable $e) {
                Log::warning('[AnalyzeKeywords] kunde inte hämta resurs', ['site_id' => $site->id, 'post_id' => (int)$pid, 'type' => $ptype, 'error' => $e->getMessage()]);
                continue;
            }

            if (empty($doc['id'])) continue;

            $url = (string)($doc['url'] ?? $site->url);
            $title = trim((string)($doc['title'] ?? ''));
            $excerpt = trim((string)($doc['excerpt'] ?? ''));
            $html = (string)($doc['html'] ?? '');
            $text = Str::of($html)->stripTags()->squish()->limit(4000);

            $rankInfo = $list->map(fn($r) => ['keyword' => $r->keyword, 'position' => $r->position])->values()->all();

            $prompt = "Du är en svensk SEO-specialist. Professionell ton, inga meta-fraser.\n".
                "Uppgift: föreslå nya relevanta nyckelord (5–10) och optimera meta-title (max 60 tecken) samt meta-description (max 155 tecken).\n".
                "Data:\nTitel: {$title}\nUtdrag: {$excerpt}\nInnehåll (trimmat): {$text}\nRanking: ".json_encode($rankInfo, JSON_UNESCAPED_UNICODE)."\n".
                "Returnera ENDAST JSON: {\"keywords\":[],\"title\":{\"current\":\"\",\"suggested\":\"\"},\"meta\":{\"current\":\"\",\"suggested\":\"\"},\"insights\":[]}";

            $out = $prov->generate($prompt, ['max_tokens' => 600, 'temperature' => 0.5]);
            $json = trim(Str::of($out)->after('{')->beforeLast('}'));
            $obj = json_decode('{'.$json.'}', true);
            if (!is_array($obj)) {
                Log::info('[AnalyzeKeywords] ogiltig JSON från AI, hoppar post', ['site_id' => $site->id, 'post_id' => (int)$pid]);
                continue;
            }

            KeywordSuggestion::updateOrCreate(
                ['site_id' => $site->id, 'wp_post_id' => (int)$pid, 'wp_type' => $ptype],
                [
                    'url' => $url,
                    'current' => [
                        'title' => $title,
                        'meta'  => $excerpt,
                        'keywords' => [],
                    ],
                    'suggested' => [
                        'title' => Arr::get($obj, 'title.suggested'),
                        'meta'  => Arr::get($obj, 'meta.suggested'),
                        'keywords' => Arr::get($obj, 'keywords', []),
                    ],
                    'insights' => Arr::get($obj, 'insights', []),
                    'status' => 'new',
                ]
            );

            $usage->increment($customer->id, 'ai.generate', now()->format('Y-m'), 1);
        }
    }
}
