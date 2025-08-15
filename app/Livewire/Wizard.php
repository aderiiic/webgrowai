<?php

namespace App\Livewire;

use App\Models\Customer;
use App\Models\Site;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class Wizard extends Component
{
    public int $step = 1;
    public string $customer_name = '';
    public string $customer_email = '';
    public string $site_name = '';
    public string $site_url = '';

    public function mount(): void
    {
        $this->step = auth()->user()->onboarding_step > 0 ? auth()->user()->onboarding_step : 1;
    }

    public function createCustomer(): void
    {
        $this->validate([
            'customer_name' => 'required|string|min:2',
            'customer_email' => 'nullable|email',
        ]);

        $customer = Customer::create([
            'name' => $this->customer_name,
            'contact_email' => $this->customer_email ?: auth()->user()->email,
        ]);
        $customer->users()->attach(auth()->id(), ['role_in_customer' => 'owner']);

        auth()->user()->update(['onboarding_step' => 2]);
        $this->step = 2;
    }

    public function createSite(): void
    {
        $this->validate([
            'site_name' => 'required|string|min:2',
            'site_url' => 'required|url',
        ]);

        $customer = auth()->user()->customers()->first(); // första kunden i MVP
        $site = $customer->sites()->create([
            'name' => $this->site_name,
            'url' => $this->site_url,
            'public_key' => Str::uuid()->toString(),
            'secret' => Str::random(32),
        ]);

        auth()->user()->update(['onboarding_step' => 3]);
        $this->step = 3;
    }

    public function finish()
    {
        auth()->user()->update(['onboarding_step' => 3]);

        return $this->redirectRoute('dashboard');
    }


    public function render()
    {
        return view('livewire.wizard');
    }
}
