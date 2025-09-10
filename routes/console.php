<?php

use App\Jobs\GenerateWeeklyDigestJob;
use App\Jobs\GenerateWeeklyPlanJob;
use App\Jobs\RecalculateLeadScoresJob;
use App\Models\Customer;
use App\Models\Site;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Manuellt kommando för veckoplaner (används vid behov)
Artisan::command('generate:weekly-plan', function () {
    $count = 0;
    Customer::query()->where('status','active')->pluck('id')->each(function ($cid) use (&$count) {
        dispatch(new GenerateWeeklyPlanJob((int) $cid))->onQueue('ai');
        $count++;
    });
    $this->info("Köade veckoplaner för {$count} kund(er).");
})->describe('Queue weekly campaign ideas for all active customers');

// Veckodigest (schemalagt)
Artisan::command('weekly:digest {tag=monday}', function (string $tag) {
    $count = 0;
    $valid = in_array($tag, ['monday','friday'], true) ? $tag : 'monday';

    Site::query()
        ->whereHas('customer', fn($q) => $q->where('status', 'active'))
        ->pluck('id')
        ->each(function ($sid) use (&$count, $valid) {
            dispatch(new GenerateWeeklyDigestJob((int) $sid, $valid))->onQueue('ai');
            $count++;
        });

    $this->info("Köade veckodigest ({$valid}) för {$count} site(s).");
})->describe('Queue weekly digest for all sites belonging to active customers');

// Schemalägg ENDAST weekly:digest
Schedule::command('weekly:digest monday')->mondays()->at('08:00');
Schedule::command('weekly:digest friday')->fridays()->at('15:00');
Schedule::command('suggestions:purge-expired')->dailyAt('03:10');

Schedule::command('social:process-scheduled')->everyMinute()->withoutOverlapping()->onOneServer();
Schedule::command('metrics:refresh-recent --hours=72 --stale=6')
    ->hourly()
    ->withoutOverlapping()
    ->onOneServer();

//Schedule::job(new \App\Jobs\ProcessScheduledPublicationsJob())->everyMinute();
Schedule::job(new \App\Jobs\RecalculateLeadScoresJob())->cron('0 */6 * * *')->withoutOverlapping()->onOneServer();

Artisan::command('leads:recalculate {--sync}', function () {
    $job = new RecalculateLeadScoresJob();

    if ($this->option('sync')) {
        Bus::dispatchSync($job);
        $this->info('Körde RecalculateLeadScoresJob synkront.');
    } else {
        dispatch($job); // ev. ->onQueue('default')
        $this->info('Köade RecalculateLeadScoresJob.');
    }
})->describe('Recalculate lead scores for all leads now');

Artisan::command('weekly:digest:one {siteId} {tag=monday}', function (int $siteId, string $tag) {
    $valid = in_array($tag, ['monday','friday'], true) ? $tag : 'monday';

    dispatch(new App\Jobs\GenerateWeeklyDigestJob($siteId, $valid))->onQueue('ai');

    $this->info("Köade veckodigest ({$valid}) för site {$siteId}.");
})->describe('Queue weekly digest for one site');
