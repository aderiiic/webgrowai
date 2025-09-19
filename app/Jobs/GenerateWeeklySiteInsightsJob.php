<?php

namespace App\Jobs;

use App\Models\Site;
use App\Models\SiteInsight;
use App\Services\Insights\InsightsGenerator;
use App\Services\Insights\TrendsCollector;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class GenerateWeeklySiteInsightsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public int $siteId,
        public ?string $weekStartYmd = null,
        public bool $forceRefresh = false
    ) {
        $this->onQueue('ai');
        $this->afterCommit();
    }

    public function handle(InsightsGenerator $gen, TrendsCollector $trendsCollector): void
    {
        Log::info("Starting insights generation", ['site_id' => $this->siteId]);

        $site = Site::find($this->siteId);
        if (!$site) {
            Log::warning("Site not found", ['site_id' => $this->siteId]);
            return;
        }

        $weekStart = $this->weekStartYmd
            ? Carbon::parse($this->weekStartYmd)->startOfWeek(Carbon::MONDAY)
            : Carbon::now()->startOfWeek(Carbon::MONDAY);

        Log::info("Generating insights", [
            'site_id'       => $site->id,
            'site_name'     => $site->name,
            'week_start'    => $weekStart->toDateString(),
            'force_refresh' => $this->forceRefresh,
        ]);

        // AI‑payload
        $payload = $gen->generateForWeek($site, $weekStart);

        // Trenddata (force vid behov)
        $trendsData = $trendsCollector->collectTrendsData($site, $this->forceRefresh);

        $siteInsight = SiteInsight::updateOrCreate(
            ['site_id' => $site->id, 'week_start' => $weekStart->toDateString()],
            [
                'payload'      => $payload,
                'trends_data'  => $trendsData,
                'model'        => $payload['model'] ?? null,
                'generated_at' => now(),
            ]
        );

        Log::info("Insights generated successfully", [
            'site_id'               => $site->id,
            'insight_id'            => $siteInsight->id,
            'topics_count'          => count($payload['topics'] ?? []),
            'trending_topics_count' => count($trendsData['trending_topics'] ?? []),
        ]);

        // broadcasta ett event om man vill slippa polling i UI:t.
        // event(new \App\Events\InsightsGenerated($site->id, $siteInsight->id));
    }
}
