<?php

namespace App\Livewire\Account;

use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class Upgrade extends Component
{
    public array $plans = [];
    public string $billing_cycle = 'monthly';

    public ?array $currentSubscription = null;
    public ?int $currentPlanId = null;

    // För jämförelsetabell
    public array $planFeatures = [];

    public function mount(): void
    {
        // Ladda nuvarande prenumeration först
        $this->loadCurrentSubscription();

        // Hämta alla aktiva planer + inkludera nuvarande plan även om den är inaktiv
        $this->loadPlans();

        // Hämta features för alla planer
        $this->loadPlanFeatures();
    }

    private function loadPlans(): void
    {
        $query = DB::table('plans')
            ->select('id', 'name', 'price_monthly', 'price_yearly', 'stripe_price_monthly', 'stripe_price_yearly');

        // Om användaren har en inaktiv plan, inkludera den ändå
        if ($this->currentPlanId) {
            $query->where(function($q) {
                $q->where('is_active', true)
                    ->orWhere('id', $this->currentPlanId);
            });
        } else {
            $query->where('is_active', true);
        }

        $this->plans = $query
            ->orderBy('price_monthly')
            ->get()
            ->map(fn($p) => [
                'id' => (int)$p->id,
                'name' => (string)$p->name,
                'price_monthly' => (int)$p->price_monthly,
                'price_yearly'  => (int)$p->price_yearly,
                'stripe_price_monthly' => (string)($p->stripe_price_monthly ?? ''),
                'stripe_price_yearly'  => (string)($p->stripe_price_yearly ?? ''),
            ])
            ->toArray();
    }

    private function loadCurrentSubscription(): void
    {
        $user = auth()->user();
        if (!$user) {
            return;
        }

        // Försök först hämta från Cashiers subscriptions-tabell
        if ($user->subscribed('default')) {
            $subscription = $user->subscription('default');
            $stripePriceId = $subscription->stripe_price ?? null;

            if ($stripePriceId) {
                $plan = DB::table('plans')
                    ->where(function($q) use ($stripePriceId) {
                        $q->where('stripe_price_monthly', $stripePriceId)
                            ->orWhere('stripe_price_yearly', $stripePriceId);
                    })
                    ->first();

                if ($plan) {
                    $this->setCurrentSubscription($plan, $stripePriceId, $subscription);
                    return;
                }
            }
        }

        // Annars försök hämta från app_subscriptions (din egen tabell)
        $customer = DB::table('customers')
            ->join('customer_user', 'customers.id', '=', 'customer_user.customer_id')
            ->where('customer_user.user_id', $user->id)
            ->select('customers.*')
            ->first();

        if (!$customer) {
            return;
        }

        $appSubscription = DB::table('app_subscriptions')
            ->where('customer_id', $customer->id)
            ->where('status', 'active')
            ->orderByDesc('id')
            ->first();

        if ($appSubscription) {
            $plan = DB::table('plans')->find($appSubscription->plan_id);

            if ($plan) {
                $this->currentPlanId = $plan->id;
                $isYearly = $appSubscription->billing_cycle === 'annual';

                $this->currentSubscription = [
                    'plan_id' => $plan->id,
                    'plan_name' => $plan->name,
                    'is_yearly' => $isYearly,
                    'price' => $isYearly ? $plan->price_yearly : $plan->price_monthly,
                    'ends_at' => $appSubscription->current_period_end ?? null,
                    'on_grace_period' => false,
                ];

                $this->billing_cycle = $isYearly ? 'annual' : 'monthly';
            }
        }
    }

    private function setCurrentSubscription($plan, $stripePriceId, $subscription): void
    {
        $this->currentPlanId = $plan->id;
        $isYearly = $stripePriceId === $plan->stripe_price_yearly;

        $this->currentSubscription = [
            'plan_id' => $plan->id,
            'plan_name' => $plan->name,
            'is_yearly' => $isYearly,
            'price' => $isYearly ? $plan->price_yearly : $plan->price_monthly,
            'ends_at' => $subscription->ends_at?->format('Y-m-d'),
            'on_grace_period' => $subscription->onGracePeriod(),
        ];

        $this->billing_cycle = $isYearly ? 'annual' : 'monthly';
    }

    private function loadPlanFeatures(): void
    {
        // Hämta alla features för varje plan (inklusive inaktiva planer om de är nuvarande)
        $planIds = collect($this->plans)->pluck('id')->toArray();

        if (empty($planIds)) {
            return;
        }

        $features = DB::table('plan_features')
            ->whereIn('plan_id', $planIds)
            ->where('is_enabled', true)
            ->get()
            ->groupBy('plan_id');

        foreach ($this->plans as $plan) {
            $planFeatures = $features->get($plan['id'], collect());

            $this->planFeatures[$plan['id']] = $planFeatures
                ->keyBy('key')
                ->map(fn($f) => [
                    'enabled' => true,
                    'limit' => $f->limit_value ?? null,
                ])
                ->toArray();
        }
    }

    public function render()
    {
        return view('livewire.account.upgrade');
    }
}
