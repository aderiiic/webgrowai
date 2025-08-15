<?php

use App\Jobs\GenerateWeeklyDigestJob;
use App\Jobs\GenerateWeeklyPlanJob;
use App\Models\Customer;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
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

    Customer::query()->where('status','active')->pluck('id')->each(function ($cid) use (&$count, $valid) {
        dispatch(new GenerateWeeklyDigestJob((int)$cid, $valid))->onQueue('ai');
        $count++;
    });

    $this->info("Köade veckodigest ({$valid}) för {$count} kund(er).");
})->describe('Queue weekly digest for all active customers');

// Schemalägg ENDAST weekly:digest
Schedule::command('weekly:digest monday')->mondays()->at('08:00');
Schedule::command('weekly:digest friday')->fridays()->at('15:00');

Schedule::job(new \App\Jobs\ProcessScheduledPublicationsJob())->everyMinute();
Schedule::job(new \App\Jobs\RecalculateLeadScoresJob())->cron('0 */6 * * *');
