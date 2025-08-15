<?php

namespace App\Livewire\Leads;

use App\Models\Lead;
use App\Models\LeadEvent;
use App\Models\LeadScore;
use App\Support\CurrentCustomer;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class Detail extends Component
{
    public Lead $lead;

    public function mount(int $id, CurrentCustomer $current): void
    {
        $this->lead = Lead::with('site')->findOrFail($id);
        $customer = $current->get();
        abort_unless($customer && $customer->sites()->whereKey($this->lead->site_id)->exists(), 403);
    }

    public function render()
    {
        $score = LeadScore::where('lead_id', $this->lead->id)->first();
        $events = LeadEvent::where('lead_id', $this->lead->id)->latest('occurred_at')->limit(100)->get();

        return view('livewire.leads.detail', compact('score','events'));
    }
}
