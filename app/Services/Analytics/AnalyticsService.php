<?php
// app/Services/Analytics/AnalyticsService.php

namespace App\Services\Analytics;

use App\Models\ContentPublication;
use App\Models\Integration;
use App\Models\SocialIntegration;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AnalyticsService
{
    public function __construct(private Ga4Client $ga4) {}

    // Enkel, cache-vänlig: hämta webb-metrics beroende på provider
    public function getWebsiteMetrics(int $customerId, ?int $siteId): array
    {
        // Upptäck provider (wordpress/shopify/standalone) på aktiv sajt
        $integration = $this->getSiteIntegration($customerId, $siteId);
        if (!$integration) {
            return [
                'connected'   => false,
                'visitors_7d' => 0,
                'sessions_7d' => 0,
                'trend_pct'   => 0,
            ];
        }

        if ($siteId && $this->ga4->isConnected($siteId)) {
            // 7 dagar bakåt
            return $this->ga4->fetchOverview($siteId, now()->subDays(7)->toDateString());
        }

        // Här kan du plugga in faktiska API-anrop eller läsa från snapshots-tabell
        $last7 = $this->snapshotSum('web_visitors', $customerId, $siteId, days: 7);
        $prev7 = $this->snapshotSum('web_visitors', $customerId, $siteId, days: 14) - $last7;

        $sessions7 = $this->snapshotSum('web_sessions', $customerId, $siteId, days: 7);

        $trend = $this->pct($last7, $prev7);

        return [
            'connected'   => true,
            'visitors_7d' => $last7,
            'sessions_7d' => $sessions7,
            'trend_pct'   => $trend,
        ];
    }

    public function getPublicationsMetrics(int $customerId, ?int $siteId): array
    {
        $from = Carbon::now()->subDays(30);
        $q = ContentPublication::query()
            ->whereHas('content', function ($c) use ($customerId, $siteId) {
                $c->where('customer_id', $customerId);
                if ($siteId) $c->where('site_id', $siteId);
            })
            ->where('created_at', '>=', $from);

        $published = (clone $q)->where('status', 'published')->count();
        $failed = (clone $q)->where('status', 'failed')->count();

        // Genomsnitt/vecka senaste 30d
        $avgPerWeek = round($published / max(1, (30/7)), 1);

        return [
            'published_30d' => $published,
            'failed_30d'    => $failed,
            'avg_per_week'  => $avgPerWeek,
        ];
    }

    public function getSocialMetrics(int $customerId, ?int $siteId): array
    {
        $providers = ['facebook', 'instagram', 'linkedin'];
        $out = [];
        foreach ($providers as $p) {
            $int = $this->getSocialIntegration($customerId, $siteId, $p);
            if (!$int) {
                $out[$p] = ['connected' => false];
                continue;
            }
            // Läs från snapshots eller API (reach/engagement 7d)
            $reach = $this->snapshotSum("{$p}_reach", $customerId, $siteId, days: 7);
            $eng = $this->snapshotSum("{$p}_engagement", $customerId, $siteId, days: 7);

            $out[$p] = [
                'connected'  => true,
                'reach'      => $reach,
                'engagement' => $eng,
            ];
        }
        return $out;
    }

    // Avancerade delar (Pro)
    public function getTopContent(int $customerId, ?int $siteId): array
    {
        // Exempel: ranka senaste 30d publiceringar på social reach + webbpageviews
        // Här läses from snapshots för snabbhet
        $rows = DB::table('analytics_snapshots')
            ->select('title', DB::raw('SUM(score) as s'))
            ->where('customer_id', $customerId)
            ->when($siteId, fn($w) => $w->where('site_id', $siteId))
            ->where('metric', 'content_score_30d')
            ->where('date', '>=', Carbon::now()->subDays(30)->toDateString())
            ->groupBy('title')
            ->orderByDesc('s')
            ->limit(10)
            ->get();

        return $rows->map(fn($r) => ['title' => $r->title, 'score' => (int)$r->s])->toArray();
    }

    public function getBestPostTimes(int $customerId, ?int $siteId): array
    {
        // Aggregerad “bästa timmen per veckodag” (preberäknat i snapshots)
        $rows = DB::table('analytics_snapshots')
            ->select('day', 'hour')
            ->where('customer_id', $customerId)
            ->when($siteId, fn($w) => $w->where('site_id', $siteId))
            ->where('metric', 'best_post_time')
            ->orderBy('day')
            ->get();

        return $rows->map(fn($r) => ['day' => $r->day, 'hour' => $r->hour])->toArray();
    }

    public function getEngagementTrend(int $customerId, ?int $siteId): array
    {
        $last30 = $this->snapshotSum('social_engagement', $customerId, $siteId, 30);
        $prev30 = $this->snapshotSum('social_engagement', $customerId, $siteId, 60) - $last30;
        return ['pct_30d' => $this->pct($last30, $prev30)];
    }

    public function compareSites(int $customerId): array
    {
        // Summera ett kompositscore per sajt (web + social + pub)
        $rows = DB::table('analytics_snapshots')
            ->select('site_id', DB::raw('SUM(score) as s'))
            ->where('customer_id', $customerId)
            ->where('metric', 'site_score_30d')
            ->where('date', '>=', Carbon::now()->subDays(30)->toDateString())
            ->groupBy('site_id')
            ->orderByDesc('s')
            ->get();

        // Hämta visningsnamn
        $siteNames = DB::table('sites')->whereIn('id', $rows->pluck('site_id'))->pluck('name', 'id');

        return $rows->map(fn($r) => [
            'site_id'   => $r->site_id,
            'site_name' => $siteNames[$r->site_id] ?? ('Sajt #' . $r->site_id),
            'score'     => (int)$r->s,
        ])->toArray();
    }

    public function getInsightsSummary(int $customerId, ?int $siteId): array
    {
        // Enkla heuristiska tips baserat på trend/summor
        $tips = [];
        $pub = $this->getPublicationsMetrics($customerId, $siteId);
        if (($pub['published_30d'] ?? 0) < 4) {
            $tips[] = 'Öka publiceringstakten till minst 1 gång/vecka för bättre räckvidd.';
        }
        $social = $this->getSocialMetrics($customerId, $siteId);
        if (($social['instagram']['engagement'] ?? 0) > ($social['facebook']['engagement'] ?? 0)) {
            $tips[] = 'Instagram presterar bättre denna period – prioritera stories/reels kommande vecka.';
        }
        $web = $this->getWebsiteMetrics($customerId, $siteId);
        if (($web['trend_pct'] ?? 0) < 0) {
            $tips[] = 'Fallande trafik – se över senaste inläggens CTA och dela dem i sociala kanaler.';
        }
        return $tips;
    }

    // Helpers

    private function pct(int $latest, int $prev): int
    {
        if ($prev <= 0) return $latest > 0 ? 100 : 0;
        return (int) round((($latest - $prev) / max(1, $prev)) * 100);
    }

    private function getSiteIntegration(int $customerId, ?int $siteId): ?object
    {
        if (!$siteId) return null;
        $int = Integration::where('site_id', $siteId)->first();
        if (!$int) return null;
        // Optionellt: säkerställ att sajten tillhör kunden (om relation finns)
        return (object)[
            'provider' => $int->provider, // 'wordpress'|'shopify'|'standalone'
        ];
    }

    private function getSocialIntegration(int $customerId, ?int $siteId, string $provider): ?object
    {
        if (!$siteId) return null;
        $int = SocialIntegration::where('site_id', $siteId)->where('provider', $provider)->first();
        if (!$int) return null;
        return (object)[
            'provider'     => $provider,
            'access_token' => 'connected', // maska alltid
        ];
    }

    private function snapshotSum(string $metric, int $customerId, ?int $siteId, int $days): int
    {
        $from = Carbon::now()->subDays($days)->toDateString();
        $q = DB::table('analytics_snapshots')
            ->where('customer_id', $customerId)
            ->where('metric', $metric)
            ->where('date', '>=', $from);

        if ($siteId) $q->where('site_id', $siteId);

        return (int) $q->sum('value');
    }

    public function getGa4ForUrls(?int $siteId, array $urls): array
    {
        if (!$siteId || empty($urls) || !$this->ga4->isConnected($siteId)) return [];
        return $this->ga4->fetchByUrls($siteId, $urls, now()->subDays(7)->toDateString());
    }
}
