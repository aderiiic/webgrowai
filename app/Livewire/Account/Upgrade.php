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

    // UI: estimat
    public int $estimate_amount = 0; // ören
    public string $estimate_text = '';

    public function mount(): void
    {
        $this->plans = DB::table('plans')->where('is_active', true)->orderBy('price_monthly')->get()->map(fn($p) => [
            'id' => $p->id,
            'name' => $p->name,
            'price_monthly' => (int)$p->price_monthly,
            'price_yearly'  => (int)$p->price_yearly,
        ])->toArray();
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
