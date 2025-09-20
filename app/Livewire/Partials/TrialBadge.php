<?php

namespace App\Livewire\Partials;

use App\Services\Billing\PlanService;
use App\Support\CurrentCustomer;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class TrialBadge extends Component
{
    public ?string $planName = null;
    public ?string $status = null; // trial|active|paused|cancelled
    public ?int $daysLeft = null;

    public function mount(PlanService $plans, CurrentCustomer $current): void
    {
        $customer = $current->get();
        if (!$customer) {
            return;
        }

        $sub = $plans->getSubscription($customer);
        $this->status = $sub->status ?? null;

        if ($sub?->plan_id) {
            $this->planName = (string) DB::table('plans')->where('id', $sub->plan_id)->value('name');
        }

        if ($sub?->status === 'trial' && $sub?->trial_ends_at) {
            $ends = \Illuminate\Support\Carbon::parse($sub->trial_ends_at);
            $this->daysLeft = now()->lt($ends) ? now()->diffInDays($ends) : 0;
        }
    }

    public function render()
    {
        return view('livewire.partials.trial-badge');
    }
}
