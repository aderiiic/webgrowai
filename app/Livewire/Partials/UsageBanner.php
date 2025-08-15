<?php

namespace App\Livewire\Partials;

use App\Services\Billing\PlanService;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class UsageBanner extends Component
{
    public array $alerts = []; // [{type: warn|stop, label, used, quota, pct}]

    private array $featureMap = [
        'ai.generate'   => 'AI‑genereringar',
        'ai.publish.wp' => 'WP‑publiceringar',
        'seo.audit'     => 'SEO‑audits',
        'leads.events'  => 'Lead events',
    ];

    public function mount(PlanService $plans): void
    {
        $customer = app(\App\Support\CurrentCustomer::class)->get();
        if (!$customer) return;

        $period = now()->format('Y-m');
        $alerts = [];

        foreach ($this->featureMap as $key => $label) {
            $quota = $plans->getQuota($customer, $key);
            if ($quota === null) continue;

            $used = (int) (DB::table('usage_metrics')
                ->where('customer_id', $customer->id)
                ->where('period', $period)
                ->where('metric_key', $key)
                ->value('used_value') ?? 0);

            if ($quota <= 0) continue;

            $pct = min(100, (int) round(($used / $quota) * 100));
            if ($pct >= 80) {
                $alerts[] = [
                    'type'  => $pct >= 100 ? 'stop' : 'warn',
                    'label' => $label,
                    'used'  => $used,
                    'quota' => $quota,
                    'pct'   => $pct,
                ];
            }
        }

        // Sites/users – visa endast på 100% (hård gräns)
        foreach (['sites' => 'Sajter', 'users' => 'Användare'] as $key => $label) {
            $quota = $plans->getQuota($customer, $key);
            if ($quota === null) continue;
            $used = $key === 'sites'
                ? DB::table('sites')->where('customer_id', $customer->id)->count()
                : DB::table('customer_user')->where('customer_id', $customer->id)->count();

            if ($quota > 0 && $used >= $quota) {
                $alerts[] = [
                    'type'  => 'stop',
                    'label' => $label,
                    'used'  => $used,
                    'quota' => $quota,
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
