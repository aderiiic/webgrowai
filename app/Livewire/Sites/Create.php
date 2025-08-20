<?php

namespace App\Livewire\Sites;

use App\Models\Site;
use App\Services\Billing\PlanService;
use App\Support\CurrentCustomer;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class Create extends Component
{
    public string $name = '';
    public string $url = '';

    public function mount(CurrentCustomer $current, PlanService $plans): void
    {
        $customer = $current->get();
        abort_unless($customer, 403);

        $quota = $plans->getQuota($customer, 'sites');
        if ($quota !== null) {
            $count = $customer->sites()->count();
            if ($count >= $quota) {
                session()->flash('error', 'Du har uppnått max antal sajter för din plan. Uppgradera för att lägga till fler.');
                $this->redirectRoute('sites.index');
            }
        }
    }

    public function save(CurrentCustomer $current, PlanService $plans): void
    {
        $customer = $current->get();
        abort_unless($customer, 403);

        // För‑kontroll igen vid spara (race-säkerhet)
        $quota = $plans->getQuota($customer, 'sites');
        if ($quota !== null) {
            $count = $customer->sites()->count();
            if ($count >= $quota) {
                session()->flash('error', 'Du har uppnått max antal sajter för din plan.');
                return;
            }
        }

        $this->validate([
            'name' => 'required|string|max:120',
            'url'  => 'required|url|max:1024',
        ]);

        $site = Site::create([
            'customer_id' => $customer->id,
            'name' => $this->name,
            'url'  => $this->url,
        ]);

        session()->flash('success', 'Sajt skapad.');

        // NYTT: skicka direkt till "Koppla integration" för nya sajten
        $this->redirectRoute('sites.integrations.connect', ['site' => $site->id]);
    }

    public function render()
    {
        return view('livewire.sites.create');
    }
}
