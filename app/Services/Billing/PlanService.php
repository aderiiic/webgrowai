<?php

namespace App\Services\Billing;

use App\Models\Customer;
use Illuminate\Support\Facades\DB;

class PlanService
{
    public function getSubscription(Customer $customer): ?object
    {
        return DB::table('subscriptions')->where('customer_id', $customer->id)->orderByDesc('id')->first();
    }

    public function inTrial(?object $sub): bool
    {
        return $sub && $sub->status === 'trial' && $sub->trial_ends_at && now()->lte($sub->trial_ends_at);
    }

    public function hasAccess(Customer $customer): bool
    {
        $sub = $this->getSubscription($customer);
        if (!$sub) return false;
        if ($sub->status === 'active') return true;
        return $this->inTrial($sub);
    }

    public function getPlanId(?object $sub): ?int
    {
        return $sub?->plan_id ?? null;
    }

    public function getQuota(Customer $customer, string $featureKey): ?int
    {
        $sub = $this->getSubscription($customer);
        $planId = $this->getPlanId($sub);
        if (!$planId) return null;
        $feat = DB::table('plan_features')
            ->where('plan_id', $planId)
            ->where('key', $featureKey)
            ->where('is_enabled', true)
            ->first();
        if (!$feat) return null;
        return is_numeric($feat->limit_value) ? (int)$feat->limit_value : null;
    }
}
