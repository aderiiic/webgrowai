<?php

namespace App\Services\Billing;

use App\Models\Customer;
use App\Models\OveragePermission;
use Illuminate\Support\Facades\DB;

class QuotaGuard
{
    public function __construct(private PlanService $plans) {}

    /**
     * @throws \RuntimeException
     */
    public function checkOrFail(Customer $customer, string $metricKey): void
    {
        if (!$this->plans->hasAccess($customer)) {
            throw new \RuntimeException('Kontot är pausat. Välj en plan för att fortsätta.');
        }

        $quota = $this->plans->getQuota($customer, $metricKey);
        if ($quota === null) return; // ej kvoterad/obegränsad

        $period = now()->format('Y-m');
        $used = (int) (DB::table('usage_metrics')
            ->where('customer_id', $customer->id)
            ->where('period', $period)
            ->where('metric_key', $metricKey) // FIX: metric_key
            ->value('used_value') ?? 0);

        if ($used >= $quota) {
            $ov = OveragePermission::where('customer_id', $customer->id)->where('period', $period)->first();
            if (!$ov || !$ov->approved) {
                throw new \RuntimeException('Kvotgräns uppnådd. Uppgradera plan eller godkänn extraanvändning.');
            }
        }
    }
}
