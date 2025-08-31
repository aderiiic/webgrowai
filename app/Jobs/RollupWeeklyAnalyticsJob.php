<?php

namespace App\Jobs;

use App\Models\Customer;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\DB;

class RollupWeeklyAnalyticsJob implements ShouldQueue
{
    use Queueable, InteractsWithQueue;

    public function __construct(public string $weekEnding /* YYYY-MM-DD (söndag) */) {}

    public function handle(): void
    {
        // Exempel: beräkna best_post_time, content_score_30d, site_score_30d från analytics_snapshots
        // Skriv aggregerade rader (title, score, day, hour) enligt ditt UI-behov.
        // Nedan bara struktur – fyll med dina faktiska formler.

        Customer::query()->with(['sites:id,customer_id'])->chunkById(200, function ($customers) {
            foreach ($customers as $customer) {
                foreach ($customer->sites as $site) {
                    // Skriv t.ex. best_post_time för 7d rullande
                    // DB::table('analytics_snapshots')->upsert([...], [...], [...]);
                }
            }
        });
    }
}
