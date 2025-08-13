<?php

namespace App\Livewire\Dashboard;

use App\Support\CurrentCustomer;
use Livewire\Attributes\Layout;
use Livewire\Component;

class ActiveCustomerCard extends Component
{
    public function render(CurrentCustomer $current)
    {
        $customer = $current->get()?->loadMissing('sites');

        return view('livewire.dashboard.active-customer-card', [
            'customer' => $customer,
            'sites'    => $customer?->sites ?? collect(),
        ]);
    }
}
