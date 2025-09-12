<?php

namespace App\Console\Commands;

use App\Jobs\GenerateWeeklySiteInsightsJob;
use App\Models\Site;
use Illuminate\Console\Command;

class GenerateSiteInsights extends Command
{
    protected $signature = 'insights:generate {siteId?} {--week=}';
    protected $description = 'Generera veckans insights för en eller alla sajter';

    public function handle(): int
    {
        $week = $this->option('week'); // YYYY-MM-DD (måndag)
        $siteId = $this->argument('siteId');

        //$sites = $siteId ? Site::whereKey($siteId)->get() : Site::query()->pluck('id');
        $sites = $siteId ? Site::whereKey($siteId)->pluck('id') : Site::query()->pluck('id');

        $count = 0;
        foreach ($sites as $sid) {
            dispatch(new GenerateWeeklySiteInsightsJob((int)$sid, $week ?: null))->onQueue('ai');
            $count++;
        }

        $this->info("Köade insights för {$count} sajt(er).");
        return self::SUCCESS;
    }
}
