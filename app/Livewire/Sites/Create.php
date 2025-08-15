<?php

namespace App\Livewire\Sites;

use App\Support\CurrentCustomer;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class Create extends Component
{
    public string $name = '';
    public string $url = '';

    public function save(CurrentCustomer $current)
    {
        $this->validate([
            'name' => 'required|string|min:2|max:150',
            'url'  => 'required|url|max:255',
        ]);

        $customer = $current->get();
        abort_unless($customer, 403);

        if ($customer->sites()->where('url', rtrim($this->url, '/'))->exists()) {
            $this->addError('url', 'Denna URL finns redan i din kund.');
            return;
        }

        $customer->sites()->create([
            'name' => $this->name,
            'url' => rtrim($this->url, '/'),
            'public_key' => (string) \Str::uuid(),
            'secret' => Str::random(32),
            'status' => 'active',
        ]);

        session()->flash('success', 'Sajt skapad.');
        return $this->redirectRoute('sites.index');
    }

    public function render()
    {
        return view('livewire.sites.create');
    }
}
