<?php

namespace App\Jobs;

use App\Models\Customer;
use App\Services\Analytics\AnalyticsService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\DB;

class CollectDailyAnalyticsJob implements ShouldQueue
{
    use Queueable, InteractsWithQueue;

    public function __construct(public string $forDate /* YYYY-MM-DD */) {}

    public function handle(AnalyticsService $analytics): void
    {
        // Iterera kunder och sajter
        Customer::query()->with(['sites:id,customer_id,name'])->chunkById(200, function ($customers) use ($analytics) {
            foreach ($customers as $customer) {
                foreach ($customer->sites as $site) {
                    $siteId = (int) $site->id;

                    // Web
                    $web = $analytics->getWebsiteMetrics($customer->id, $siteId);
                    if ($web['connected']) {
                        $this->upsert($customer->id, $siteId, 'web_visitors', $web['visitors_7d'] /* placeholder: byt till “föregående dygn” */, $this->forDate);
                        $this->upsert($customer->id, $siteId, 'web_sessions', $web['sessions_7d'] /* placeholder */, $this->forDate);
                    }

                    // Social (reach/eng)
                    $soc = $analytics->getSocialMetrics($customer->id, $siteId);
                    foreach (['facebook','instagram','linkedin'] as $p) {
                        if (!empty($soc[$p]['connected'])) {
                            $this->upsert($customer->id, $siteId, "{$p}_reach", (int) $soc[$p]['reach'] /* placeholder */, $this->forDate);
                            $this->upsert($customer->id, $siteId, "{$p}_engagement", (int) $soc[$p]['engagement'] /* placeholder */, $this->forDate);
                        }
                    }

                    // Publicering – kan skrivas som dagliga summeringar om du vill grafer per dag
                    // Ex: antal publicerade/failed det datumet
                    // Tips: räkna direkt från ContentPublication filtrerat på created_at = forDate och skriv metrics:
                    // publications_published, publications_failed
                }
            }
        });
    }

    private function upsert(int $customerId, int $siteId, string $metric, int $value, string $date): void
    {
        DB::table('analytics_snapshots')->upsert([
            'customer_id' => $customerId,
            'site_id'     => $siteId,
            'date'        => $date,
            'metric'      => $metric,
            'value'       => $value,
            'created_at'  => now(),
            'updated_at'  => now(),
        ], ['customer_id','site_id','date','metric'], ['value','updated_at']);
    }
}
