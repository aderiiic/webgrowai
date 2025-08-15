<?php

namespace App\Livewire\Onboarding;

use App\Models\LeadEvent;
use App\Support\CurrentCustomer;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class Tracker extends Component
{
    public ?string $siteName = null;
    public ?string $siteKey = null;
    public string $trackUrl;
    public ?string $lastEventAt = null;
    public bool $listening = false;

    public function mount(CurrentCustomer $current): void
    {
        $customer = $current->get();
        abort_unless($customer, 403);
        $site = $customer->sites()->orderBy('id')->first();
        abort_unless($site, 404, 'Ingen sajt i aktuell kund.');

        $this->siteName = $site->name ?? ('#'.$site->id);
        $this->siteKey = $site->public_key;
        $this->trackUrl = config('app.url'); // bas-URL till appen

        $this->lastEventAt = LeadEvent::where('site_id', $site->id)->latest('occurred_at')->value('occurred_at')?->diffForHumans();
    }

    public function listen(CurrentCustomer $current): void
    {
        $this->listening = true;
        $site = $current->get()?->sites()->orderBy('id')->first();
        if (!$site) return;

        // En väldigt enkel "poll" – i UI upprepas refresh via wire:poll.10s på komponenten
        $ts = LeadEvent::where('site_id', $site->id)->latest('occurred_at')->value('occurred_at');
        $this->lastEventAt = $ts ? $ts->diffForHumans() : null;
    }

    public function render()
    {
        return view('livewire.onboarding.tracker');
    }
}
