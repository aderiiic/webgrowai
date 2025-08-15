<?php

namespace App\Services\Billing;

use Illuminate\Support\Facades\DB;

class InvoiceCalculator
{
    // Enhetspriser i ören (ex moms) – MVP
    private array $unit = [
        'ai.generate'   => 30,    // 0,30 kr/st
        'ai.publish.wp' => 80,    // 0,80 kr/st
        'seo.audit'     => 9900,  // 99 kr/st
        'leads.events'  => 1,     // 0,001 kr/event (avrundas i total)
    ];

    public function recalc(int $invoiceId): void
    {
        $inv = DB::table('invoices')->where('id', $invoiceId)->first();
        if (!$inv) return;

        $customerId = (int)$inv->customer_id;
        $period = $inv->period;

        // Hämta subscription och plan
        $sub = DB::table('subscriptions')->where('customer_id', $customerId)->orderByDesc('id')->first();
        $plan = $sub ? DB::table('plans')->where('id', $sub->plan_id)->first() : null;

        // Planbelopp – MVP: endast monthly (annual faktureras separat)
        $planAmount = 0;
        if ($plan && (($sub->billing_cycle ?? 'monthly') === 'monthly')) {
            $planAmount = (int)($plan->price_monthly ?? 0);
        }

        // Beräkna övertramp
        $features = $plan
            ? DB::table('plan_features')->where('plan_id', $plan->id)->where('is_enabled', true)->get()
            : collect();

        $lines = [];
        $addon = 0;

        foreach ($features as $feat) {
            $key = $feat->key;
            if (!array_key_exists($key, $this->unit)) continue;

            $quota = is_numeric($feat->limit_value) ? (int)$feat->limit_value : null;
            if ($quota === null) continue;

            $used = (int) (DB::table('usage_metrics')
                ->where('customer_id', $customerId)
                ->where('period', $period)
                ->where('metric_key', $key)
                ->value('used_value') ?? 0);

            $over = max(0, $used - $quota);
            if ($over > 0) {
                $unit = $this->unit[$key];
                $amount = $over * $unit;
                $addon += $amount;

                $lines[] = [
                    'type' => $key,
                    'qty' => $over,
                    'unit' => $unit,
                    'amount' => $amount,
                ];
            }
        }

        $total = $planAmount + $addon;

        DB::table('invoices')->where('id', $invoiceId)->update([
            'plan_amount'  => $planAmount,
            'addon_amount' => $addon,
            'total_amount' => $total,
            'lines'        => json_encode($lines, JSON_UNESCAPED_UNICODE),
            'updated_at'   => now(),
        ]);
    }
}
