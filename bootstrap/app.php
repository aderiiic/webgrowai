<?php

use App\Http\Middleware\EnsureOnboardingCompleted;
use App\Http\Middleware\LoadCurrentCustomer;
use App\Jobs\CollectDailyAnalyticsJob;
use App\Jobs\RollupWeeklyAnalyticsJob;
use App\Jobs\SendWeeklyAnalyticsDigestJob;
use App\Models\Customer;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\DB;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'onboarded'     => EnsureOnboardingCompleted::class,
            'paidOrTrial'   => \App\Http\Middleware\PaidOrTrial::class,
            'premium'       => App\Http\Middleware\CheckPremiumPlan::class,
            'setLocale'     => App\Http\Middleware\SetLocale::class,
            'feature.access'=> App\Http\Middleware\CheckFeatureAccess::class,
        ]);
        $middleware->appendToGroup('web', [
            LoadCurrentCustomer::class,
            App\Http\Middleware\SetLocale::class,
        ]);
    })
    ->withSchedule(function (Schedule $schedule) {
        // Pausa utgångna trials
        $schedule->call(function () {
            DB::table('subscriptions')
                ->where('status','trial')
                ->whereNotNull('trial_ends_at')
                ->where('trial_ends_at','<', now())
                ->update(['status' => 'paused']);
        })->dailyAt('02:10');

        // Skapa fakturor för förra månaden
        $schedule->call(function () {
            $period = now()->subMonth()->format('Y-m');
            $subs = DB::table('subscriptions')->where('status','active')->get();

            foreach ($subs as $sub) {
                $exists = \App\Models\Invoice::where('customer_id', $sub->customer_id)
                    ->where('period', $period)
                    ->exists();
                if ($exists) continue;

                \App\Models\Invoice::create([
                    'customer_id'  => $sub->customer_id,
                    'period'       => $period,
                    'plan_amount'  => 0,
                    'addon_amount' => 0,
                    'total_amount' => 0,
                    'currency'     => 'SEK',
                    'status'       => 'draft',
                ]);
            }
        })->monthlyOn(1, '03:05');

        $schedule->call(function () {
            app(\App\Services\Billing\UsageAlertService::class)->run();
        })->dailyAt('08:10');

        $schedule->call(function () {
            $date = now()->subDay()->toDateString();
            dispatch(new CollectDailyAnalyticsJob($date))->onQueue('analytics');
        })
            ->name('collect-daily-analytics')
            ->dailyAt('03:00')
            ->onOneServer()
            ->withoutOverlapping();

        $schedule->call(function () {
            $weekEnding = now()->subDay()->toDateString();
            dispatch(new RollupWeeklyAnalyticsJob($weekEnding))->onQueue('analytics');
        })
            ->name('rollup-weekly-analytics')
            ->mondays()
            ->at('03:15')
            ->onOneServer()
            ->withoutOverlapping();

        $schedule->call(function () {
            Customer::query()->chunkById(200, function ($customers) {
                foreach ($customers as $c) {
                    dispatch(new SendWeeklyAnalyticsDigestJob($c->id))->onQueue('mail');
                }
            });
        })
            ->name('send-weekly-digest')
            ->mondays()
            ->at('03:45')
            ->onOneServer()
            ->withoutOverlapping();

    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
