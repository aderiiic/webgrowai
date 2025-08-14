<?php

namespace App\Livewire\Dashboard;

use App\Models\WeeklyPlan;
use App\Support\CurrentCustomer;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class WeeklyDigestHistory extends Component
{
    use WithPagination;

    public ?string $filterTag = '';  // monday|friday|''

    public function render(CurrentCustomer $current)
    {
        $customer = $current->get();
        abort_unless($customer, 403);

        $q = WeeklyPlan::where('customer_id', $customer->id)
            ->when($this->filterTag !== '', fn($x) => $x->where('run_tag', $this->filterTag))
            ->latest();

        return view('livewire.dashboard.weekly-digest-history', [
            'plans' => $q->paginate(12),
        ]);
    }
}
