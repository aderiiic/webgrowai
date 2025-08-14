<?php

use App\Jobs\GenerateWeeklyPlanJob;
use App\Models\Customer;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('generate:weekly-plan', function () {
    $count = 0;
    Customer::query()->where('status','active')->pluck('id')->each(function ($cid) use (&$count) {
        dispatch(new GenerateWeeklyPlanJob((int) $cid))->onQueue('ai');
        $count++;
    });
    $this->info("Köade veckoplaner för {$count} kund(er).");
})->describe('Queue weekly campaign ideas for all active customers');

// Schemalägg körningarna
Schedule::command('generate:weekly-plan')->mondays()->at('08:00');
Schedule::command('generate:weekly-plan')->fridays()->at('15:00');
