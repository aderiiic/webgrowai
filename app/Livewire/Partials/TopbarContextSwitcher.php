<?php

namespace App\Livewire\Partials;

use App\Models\Customer;
use App\Models\Site;
use App\Support\CurrentCustomer;
use Livewire\Component;

class TopbarContextSwitcher extends Component
{
    public ?int $customerId = null;
    public ?int $siteId = null;

    public function mount(CurrentCustomer $current): void
    {
        $active = $current->get();
        $this->customerId = $active?->id;

        if ($active) {
            $this->siteId = $active->sites()->orderBy('id')->value('id');
        }
    }

    public function updatedCustomerId($value, CurrentCustomer $current): void
    {
        $user = auth()->user();
        if (!$user) return;

        $customer = Customer::find((int)$value);
        if (!$customer) return;

        // Admin får byta till valfri kund; övriga bara till sina kunder
        if ($user->isAdmin() || $user->customers()->whereKey($customer->id)->exists()) {
            $current->set($customer->id);
            // auto-välj första sajt för kunden
            $this->siteId = $customer->sites()->orderBy('id')->value('id');
            session()->flash('success', 'Bytte aktiv kund.');
            $this->dispatch('refresh'); // om du vill lyssna i sidor
        }
    }

    public function updatedSiteId($value): void
    {
        // Här kan du spara siteId i session om du har en SiteContext-tjänst.
        // För nu låter vi bara UI visa vald sajt. Implementera vid behov.
        session()->flash('success', 'Bytte aktiv sajt.');
    }

    public function getCustomersProperty()
    {
        $user = auth()->user();
        if (!$user) return collect();

        return $user->isAdmin()
            ? Customer::orderBy('name')->get()
            : $user->customers()->orderBy('name')->get();
    }

    public function getSitesProperty()
    {
        $cid = $this->customerId;
        if (!$cid) return collect();
        return Site::where('customer_id', $cid)->orderBy('name')->get();
    }

    public function render(CurrentCustomer $current)
    {
        return view('livewire.partials.topbar-context-switcher', [
            'customers' => $this->customers,
            'sites' => $this->sites,
            'active' => $current->get(),
        ]);
    }
}
