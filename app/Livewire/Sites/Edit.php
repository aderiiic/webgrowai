<?php

namespace App\Livewire\Sites;

use App\Models\Site;
use App\Support\CurrentCustomer;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class Edit extends Component
{
    public Site $site;
    public string $name = '';
    public string $url = '';
    public string $public_key = '';
    public ?string $secret = null;

    public function mount(Site $site, CurrentCustomer $current): void
    {
        $active = $current->get();
        // Admin kan redigera allt; kund måste matcha aktiv kund
        if (!auth()->user()->isAdmin()) {
            abort_unless($active && $active->id === $site->customer_id, 403);
        }

        $this->site = $site;
        $this->name = $site->name;
        $this->url = $site->url;
        $this->public_key = $site->public_key;
        $this->secret = $site->secret;
    }

    public function save(): void
    {
        $this->validate([
            'name' => 'required|string|min:2|max:150',
            'url'  => 'required|url|max:255',
        ]);

        $exists = $this->site->customer
            ->sites()
            ->where('id', '!=', $this->site->id)
            ->where('url', rtrim($this->url, '/'))
            ->exists();

        if ($exists) {
            $this->addError('url', 'Denna URL finns redan i din kund.');
            return;
        }

        $this->site->update([
            'name' => $this->name,
            'url'  => rtrim($this->url, '/'),
        ]);

        session()->flash('success', 'Sajt uppdaterad.');
        $this->redirectRoute('sites.index');
    }

    public function rotateKeys(): void
    {
        $this->site->update([
            'public_key' => (string) \Str::uuid(),
            'secret' => Str::random(32),
        ]);

        $this->public_key = $this->site->public_key;
        $this->secret = $this->site->secret;

        session()->flash('success', 'Nycklar roterade.');
    }

    public function render()
    {
        return view('livewire.sites.edit');
    }
}
