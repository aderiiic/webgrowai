<?php

namespace App\Livewire\Partials;

use App\Models\Customer;
use App\Support\CurrentCustomer;
use Livewire\Component;

class CustomerSwitcher extends Component
{
    public ?int $customerId = null;

    public function mount(CurrentCustomer $current): void
    {
        $this->customerId = $current->get()?->id;
    }

    public function updatedCustomerId($value, CurrentCustomer $current)
    {
        $user = auth()->user();
        if (!$user) return;

        $customer = Customer::find((int)$value);
        if (!$customer) return;

        if ($user->isAdmin() || $user->customers()->whereKey($customer->id)->exists()) {
            $current->set($customer->id);
            session()->flash('success', 'Bytte aktiv kund.');
            // Redirecta till samma URL för att ladda om data i rätt kontext
            return redirect(request()->header('Referer') ?: route('dashboard'));
        }
        $this->dispatch('notify', ['type' => 'error', 'message' => 'Otillåtet kundval.']);
    }

    public function getCustomersProperty()
    {
        $user = auth()->user();
        if (!$user) return collect();

        return $user->isAdmin()
            ? Customer::orderBy('name')->get()
            : $user->customers()->orderBy('name')->get();
    }

    public function render(CurrentCustomer $current)
    {
        return view('livewire.partials.customer-switcher', [
            'customers' => $this->customers,
            'active' => $current->get(),
        ]);
    }
}
