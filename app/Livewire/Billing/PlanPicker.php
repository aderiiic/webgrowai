<?php

namespace App\Livewire\Billing;

use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.guest')]
class PlanPicker extends Component
{
    public array $plans = [];
    public string $cycle = 'monthly'; // monthly|annual

    public function mount(): void
    {
        $this->plans = DB::table('plans')
            ->select('id','name','price_monthly','price_yearly','stripe_price_monthly','stripe_price_yearly')
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
    }

    public function render()
    {
        return view('livewire.billing.plan-picker');
    }
}
