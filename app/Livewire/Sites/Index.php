<?php

namespace App\Livewire\Sites;

use App\Support\CurrentCustomer;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class Index extends Component
{
    public function render(CurrentCustomer $current)
    {
        $customer = $current->get();
        abort_unless($customer, 403);

        return view('livewire.sites.index', [
            'sites' => $customer->sites()->latest()->get(),
        ]);
    }
}
