<?php

namespace App\Livewire\Admin\Plans;

use App\Models\Plan;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class Index extends Component
{
    public function render()
    {
        return view('livewire.admin.plans.index', [
            'plans' => Plan::query()->withCount('features')->orderBy('id')->get(),
        ]);
    }
}
