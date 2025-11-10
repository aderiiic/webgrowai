<?php

namespace App\Livewire\Account;

use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class Upgrade extends Component
{
    public array $plans = [];
    public ?int $desired_plan_id = null;
    public string $billing_cycle = 'monthly';
    public ?string $note = null;

    public ?array $currentSubscription = null;
    public ?int $currentPlanId = null;

    // UI: estimat
    public int $estimate_amount = 0; // ören
    public string $estimate_text = '';

    public function mount(): void
    {
        $this->plans = DB::table('plans')
            ->select('id', 'name', 'price_monthly', 'price_yearly', 'stripe_price_monthly', 'stripe_price_yearly')
            ->where('is_active', true)
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

        $this->loadCurrentSubscription();
    }

    private function loadCurrentSubscription(): void
    {
        $user = auth()->user();

        if (!$user || !$user->subscribed('default')) {
            return;
        }

        $subscription = $user->subscription('default');

        if (!$subscription) {
            return;
        }

        // Hämta Stripe Price ID från prenumerationen
        $stripePriceId = $subscription->stripe_price ?? null;

        if ($stripePriceId) {
            // Hitta plan baserat på Stripe Price ID
            $plan = DB::table('plans')
                ->where(function($q) use ($stripePriceId) {
                    $q->where('stripe_price_monthly', $stripePriceId)
                        ->orWhere('stripe_price_yearly', $stripePriceId);
                })
                ->first();

            if ($plan) {
                $this->currentPlanId = $plan->id;

                // Bestäm om det är månadsvis eller årsvis
                $isYearly = $stripePriceId === $plan->stripe_price_yearly;

                $this->currentSubscription = [
                    'plan_id' => $plan->id,
                    'plan_name' => $plan->name,
                    'is_yearly' => $isYearly,
                    'price' => $isYearly ? $plan->price_yearly : $plan->price_monthly,
                    'ends_at' => $subscription->ends_at?->format('Y-m-d'),
                    'on_grace_period' => $subscription->onGracePeriod(),
                ];

                // Sätt billing_cycle baserat på nuvarande prenumeration
                $this->billing_cycle = $isYearly ? 'annual' : 'monthly';
            }
        }
    }

    public function updatedDesiredPlanId(): void
    {
        $this->recalcEstimate();
    }

    public function updatedBillingCycle(): void
    {
        $this->recalcEstimate();
    }

    private function recalcEstimate(): void
    {
        $plan = collect($this->plans)->firstWhere('id', $this->desired_plan_id);
        if (!$plan) {
            $this->estimate_amount = 0;
            $this->estimate_text = '';
            return;
        }

        if ($this->billing_cycle === 'annual') {
            $this->estimate_amount = (int)($plan['price_yearly'] ?? 0);
            $this->estimate_text = 'Årspremie (ex moms), debiteras årligen';
        } else {
            $this->estimate_amount = (int)($plan['price_monthly'] ?? 0);
            $this->estimate_text = 'Månadspremie (ex moms), debiteras månadsvis';
        }
    }

    public function submit(): void
    {
        $customer = app(\App\Support\CurrentCustomer::class)->get();
        abort_unless($customer, 403);

        $this->validate([
            'desired_plan_id' => 'required|integer|exists:plans,id',
            'billing_cycle'   => 'required|in:monthly,annual',
        ]);

        DB::table('subscription_change_requests')->insert([
            'customer_id'      => $customer->id,
            'desired_plan_id'  => $this->desired_plan_id,
            'billing_cycle'    => $this->billing_cycle,
            'status'           => 'pending',
            'note'             => $this->note,
            'created_at'       => now(),
            'updated_at'       => now(),
        ]);

        session()->flash('success', 'Begäran skickad. Vi återkommer inom kort.');
        $this->redirectRoute('account.usage');
    }

    public function render()
    {
        // håll estimatet uppdaterat vid initial render
        $this->recalcEstimate();

        return view('livewire.account.upgrade');
    }
}
