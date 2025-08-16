<?php

namespace App\Jobs;

use App\Support\Usage;
use App\Models\KeywordSuggestion;
use App\Models\RankingSnapshot;
use App\Models\Site;
use App\Models\WpIntegration;
use App\Services\AI\AiProviderManager;
use App\Services\Billing\QuotaGuard;
use App\Services\WordPressClient;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\ModelNotFoundException;
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

    public function handle(AiProviderManager $manager, QuotaGuard $quota, Usage $usage): void
    {
        $site = Site::with('customer')->findOrFail($this->siteId);
        $customer = $site->customer;

        try {
            $integration = WpIntegration::where('site_id', $site->id)->firstOrFail();
        } catch (ModelNotFoundException $e) {
            Log::warning('[AnalyzeKeywords] ingen WP-integration kopplad, hoppar över', [
                'customer_id' => $customer?->id,
                'site_id'     => $site->id,
            ]);
            return;
        }

        $wp = WordPressClient::for($integration);
        $prov = $manager->choose(null, 'short');

        $rankings = RankingSnapshot::where('site_id', $site->id)
            ->latest('checked_at')->get()->groupBy('wp_post_id');

        if ($rankings->isEmpty()) {
            Log::info('[AnalyzeKeywords] inga ranking-snapshots hittades', [
                'site_id' => $site->id,
            ]);
            return;
        }

        foreach ($rankings as $pid => $list) {
            try {
                $quota->checkOrFail($customer, 'ai.generate');
            } catch (\Throwable $e) {
                Log::warning('[AnalyzeKeywords] blocked by quota', [
                    'customer_id' => $customer->id,
                    'site_id' => $site->id,
                    'post_id' => $pid,
                    'error' => $e->getMessage(),
                ]);
                break; // avbryt vidare genereringar denna körning
            }

            $ptype = Arr::get($list->first(), 'wp_type', 'page');

            // Använd rätt endpoint baserat på typslag
            try {
                $data = $ptype === 'post'
                    ? $wp->getPost((int)$pid)
                    : $wp->getPage((int)$pid);
            } catch (\Throwable $e) {
                Log::warning('[AnalyzeKeywords] kunde inte hämta resurs från WP', [
                    'site_id' => $site->id,
                    'post_id' => (int)$pid,
                    'type'    => $ptype,
                    'error'   => $e->getMessage(),
                ]);
                continue;
            }

            if (!$data || !isset($data['id'])) {
                continue;
            }

            $url = (string)($data['link'] ?? $site->url);
            $title = trim(strip_tags($data['title']['rendered'] ?? ''));
            $excerpt = trim(strip_tags($data['excerpt']['rendered'] ?? ''));
            $html = (string)Arr::get($data, 'content.rendered', '');
            $text = Str::of($html)->stripTags()->squish()->limit(4000);

            $rankInfo = $list->map(fn($r) => ['keyword' => $r->keyword, 'position' => $r->position])->values()->all();

            $prompt = "Du är en svensk SEO-specialist. Professionell ton, inga meta-fraser.\n".
                "Uppgift: föreslå nya relevanta nyckelord att inkludera (5–10) och optimera meta-title (max 60 tecken) och meta-description (max 155 tecken) för sidan.\n".
                "Data:\nTitel: {$title}\nUtdrag: {$excerpt}\nInnehåll (trimmat): {$text}\nRanking: ".json_encode($rankInfo, JSON_UNESCAPED_UNICODE)."\n".
                "Returnera ENDAST JSON:\n{\n".
                "  \"keywords\": [\"...\"],\n".
                "  \"title\": {\"current\": \"...\", \"suggested\": \"...\"},\n".
                "  \"meta\": {\"current\": \"...\", \"suggested\": \"...\"},\n".
                "  \"insights\": [\"...\"]\n".
                "}\n";

            $out = $prov->generate($prompt, ['max_tokens' => 600, 'temperature' => 0.5]);
            $json = trim(Str::of($out)->after('{')->beforeLast('}'));
            $json = '{'.$json.'}';
            $obj = json_decode($json, true);
            if (!is_array($obj)) {
                Log::info('[AnalyzeKeywords] ogiltig JSON från AI, hoppar post', [
                    'site_id' => $site->id,
                    'post_id' => (int)$pid,
                ]);
                continue;
            }

            KeywordSuggestion::updateOrCreate(
                ['site_id' => $site->id, 'wp_post_id' => (int)$pid, 'wp_type' => $ptype],
                [
                    'url' => $url,
                    'current' => [
                        'title' => $title,
                        'meta'  => $excerpt,  // MVP: använder excerpt som meta
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
