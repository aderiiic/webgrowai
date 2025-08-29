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
            // Försök använda redan vald sajt från sessionen
            $savedSiteId = $current->getSiteId();

            if ($savedSiteId && $active->sites()->whereKey($savedSiteId)->exists()) {
                $this->siteId = $savedSiteId;
            } else {
                // Fall tillbaka till första sajt för kunden och spara den i sessionen
                $firstId = $active->sites()->orderBy('id')->value('id');
                $this->siteId = $firstId ?: null;

                if ($firstId) {
                    $current->setSiteId((int) $firstId);
                } else {
                    $current->clearSite();
                }
            }
        }
    }

    public function updatedCustomerId($value, CurrentCustomer $current): void
    {
        $user = auth()->user();
        if (!$user) return;

        $customer = Customer::find((int) $value);
        if (!$customer) return;

        // Admin får byta till valfri kund; övriga bara till sina kunder
        if ($user->isAdmin() || $user->customers()->whereKey($customer->id)->exists()) {
            // Byt kund (nollställer site i sessionen)
            $current->set($customer->id);
            $this->customerId = $customer->id;

            // Välj första sajt för kunden och SPARA den i sessionen (eller rensa om inga sajter)
            $firstSiteId = $customer->sites()->orderBy('id')->value('id');
            $this->siteId = $firstSiteId ?: null;

            if ($firstSiteId) {
                $current->setSiteId((int) $firstSiteId);
            } else {
                $current->clearSite();
            }

            session()->flash('success', 'Bytte aktiv kund.');
            $this->dispatch('refresh');
        }
    }

    public function updatedSiteId($value, CurrentCustomer $current): void
    {
        $activeCustomer = $current->get();

        // Tomt värde => rensa vald sajt
        if (!$value) {
            $this->siteId = null;
            $current->clearSite();
            session()->flash('success', 'Rensade aktiv sajt.');
            return;
        }

        $siteId = (int) $value;

        // Validera att sajten tillhör aktiva kunden
        if ($activeCustomer && $activeCustomer->sites()->whereKey($siteId)->exists()) {
            $this->siteId = $siteId;
            $current->setSiteId($siteId);
            session()->flash('success', 'Bytte aktiv sajt.');
        } else {
            // Ogiltigt val: återställ till sparat eller första tillgängliga
            $saved = $current->getSiteId();
            if ($saved && $activeCustomer && $activeCustomer->sites()->whereKey($saved)->exists()) {
                $this->siteId = $saved;
            } else {
                $fallback = $activeCustomer?->sites()->orderBy('id')->value('id');
                $this->siteId = $fallback ?: null;
                if ($fallback) {
                    $current->setSiteId((int) $fallback);
                } else {
                    $current->clearSite();
                }
            }
        }
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
