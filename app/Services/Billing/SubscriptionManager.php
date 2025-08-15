<?php

namespace App\Services\Billing;

use Illuminate\Support\Facades\DB;

class SubscriptionManager
{
    public function applyPlanChange(int $customerId, int $planId, string $billingCycle = 'monthly'): void
    {
        $sub = DB::table('subscriptions')->where('customer_id', $customerId)->orderByDesc('id')->first();

        if ($sub) {
            DB::table('subscriptions')->where('id', $sub->id)->update([
                'plan_id' => $planId,
                'billing_cycle' => $billingCycle,
                'status' => 'active',
                'trial_ends_at' => null,
                'updated_at' => now(),
            ]);
        } else {
            DB::table('subscriptions')->insert([
                'customer_id' => $customerId,
                'plan_id'     => $planId,
                'status'      => 'active',
                'billing_cycle' => $billingCycle,
                'current_period_start' => now()->startOfMonth(),
                'current_period_end'   => now()->endOfMonth(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
