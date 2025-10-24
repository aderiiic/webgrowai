<?php

namespace App\Livewire\Partials;

use App\Services\Billing\PlanService;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class UsageBanner extends Component
{
    public array $alerts = []; // [{type: warn|stop, label, used, quota, pct}]

    public function mount(PlanService $plans): void
    {
        $customer = app(\App\Support\CurrentCustomer::class)->get();
        if (!$customer) return;

        $period = now()->format('Y-m');
        $alerts = [];

        // 1) Credits – huvudindikator
        $quota = $plans->getQuota($customer, 'credits.monthly'); // null = obegränsat
        if ($quota !== null && $quota > 0) {
            $used = (int) (DB::table('usage_metrics')
                ->where('customer_id', $customer->id)
                ->where('period', $period)
                ->where('metric_key', 'credits.used')
                ->value('used_value') ?? 0);

            $pct = (int) round(($used / max(1, $quota)) * 100);
            if ($pct >= 80) {
                $alerts[] = [
                    'type'  => $pct >= 100 ? 'stop' : 'warn',
                    'label' => 'Krediter denna månad',
                    'used'  => $used,
                    'quota' => $quota,
                    'pct'   => min(100, $pct),
                ];
            }
        }

        // 2) Sites – hård gräns
        $sitesQuota = $plans->getQuota($customer, 'sites');
        if ($sitesQuota !== null) {
            $sitesUsed = DB::table('sites')->where('customer_id', $customer->id)->count();
            if ($sitesQuota > 0 && $sitesUsed >= $sitesQuota) {
                $alerts[] = [
                    'type'  => 'stop',
                    'label' => 'Sajter',
                    'used'  => $sitesUsed,
                    'quota' => $sitesQuota,
                    'pct'   => 100,
                ];
            }
        }

        $this->alerts = $alerts;
    }

    public function render()
    {
        return view('livewire.partials.usage-banner');
    }
}
