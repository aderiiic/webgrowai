<?php

namespace App\Services\Billing;

use App\Models\Customer;
use App\Models\Plan;
use Illuminate\Support\Facades\DB;

class FeatureGuard
{
    public function __construct(
        private PlanService $planService
    ) {}

    /**
     * Kontrollera om kund har tillgång till ett feature
     */
    public function canUseFeature(Customer $customer, string $featureKey): bool
    {
        $sub = $this->planService->getSubscription($customer);

        if (!$sub) {
            return false;
        }

        $planId = $this->planService->getPlanId($sub);
        if (!$planId) {
            return false;
        }

        $feature = DB::table('plan_features')
            ->where('plan_id', $planId)
            ->where('key', $featureKey)
            ->where('is_enabled', true)
            ->first();

        return $feature !== null;
    }

    /**
     * Hämta limit-värde för ett feature
     */
    public function getFeatureLimit(Customer $customer, string $featureKey): ?int
    {
        return $this->planService->getQuota($customer, $featureKey);
    }

    /**
     * Hämta planens namn
     */
    public function getPlanName(Customer $customer): string
    {
        $sub = $this->planService->getSubscription($customer);
        $planId = $this->planService->getPlanId($sub);

        if (!$planId) {
            return 'Ingen plan';
        }

        $plan = Plan::find($planId);
        return $plan?->name ?? 'Okänd plan';
    }

    /**
     * Feature-nycklar
     */
    public const FEATURE_SOCIAL_MEDIA = 'ai.social_media';
    public const FEATURE_BLOG = 'ai.blog';
    public const FEATURE_SEO_OPTIMIZE = 'ai.seo_optimize';
    public const FEATURE_PRODUCT = 'ai.product';
    public const FEATURE_BULK_GENERATE = 'ai.bulk_generate';
    public const FEATURE_BULK_LIMIT = 'ai.bulk_limit';
}
