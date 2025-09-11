<?php

namespace App\Jobs;

use App\Models\Site;
use App\Models\SiteInsight;
use App\Services\Insights\InsightsGenerator;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Carbon;

class GenerateWeeklySiteInsightsJob implements ShouldQueue
{
    use Queueable;

    public function __construct(public int $siteId, public ?string $weekStartYmd = null)
    {
        $this->onQueue('ai');
        $this->afterCommit();
    }

    public function handle(InsightsGenerator $gen): void
    {
        $site = Site::find($this->siteId);
        if (!$site) return;

        $weekStart = $this->weekStartYmd
            ? Carbon::parse($this->weekStartYmd)->startOfWeek(Carbon::MONDAY)
            : Carbon::now()->startOfWeek(Carbon::MONDAY);

        $payload = $gen->generateForWeek($site, $weekStart);

        SiteInsight::updateOrCreate(
            ['site_id' => $site->id, 'week_start' => $weekStart->toDateString()],
            [
                'payload'      => $payload,
                'model'        => $payload['model'] ?? null,
                'generated_at' => now(),
            ]
        );
    }
}
