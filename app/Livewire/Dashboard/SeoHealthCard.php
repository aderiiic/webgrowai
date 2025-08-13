<?php

namespace App\Livewire\Dashboard;

use App\Jobs\RunSeoAuditJob;
use App\Models\SeoAudit;
use App\Support\CurrentCustomer;
use Livewire\Component;

class SeoHealthCard extends Component
{
    public ?SeoAudit $latest = null;
    public ?int $siteId = null; // vald site

    public function mount(CurrentCustomer $current): void
    {
        $customer = $current->get();
        if ($customer) {
            $this->siteId = $customer->sites()->orderBy('id')->value('id');
        }
    }

    // Ladda senaste audit för aktuell (ev. vald) site
    public function loadLatest(CurrentCustomer $current): void
    {
        $customer = $current->get();
        if (!$customer) {
            $this->latest = null;
            return;
        }

        $query = SeoAudit::whereIn('site_id', $customer->sites()->pluck('id'));
        if ($this->siteId) {
            $query->where('site_id', $this->siteId);
        }
        $this->latest = $query->latest()->first();
    }

    // När sajt byts, ladda direkt (ingen fördröjning)
    public function updatedSiteId(CurrentCustomer $current): void
    {
        $this->loadLatest($current);
    }

    public function runAudit(CurrentCustomer $current): void
    {
        $customer = $current->get();
        abort_unless($customer && $this->siteId, 403);

        dispatch((new RunSeoAuditJob($this->siteId))->onQueue('seo'));
        session()->flash('success', 'SEO audit startad för vald sajt.');
    }

    public function runAuditAll(CurrentCustomer $current): void
    {
        $customer = $current->get();
        abort_unless($customer, 403);

        $ids = $customer->sites()->pluck('id');
        foreach ($ids as $id) {
            dispatch((new RunSeoAuditJob($id))->onQueue('seo'));
        }
        session()->flash('success', 'SEO audits startade för alla sajter.');
    }

    public function render(CurrentCustomer $current)
    {
        $customer = $current->get();
        $sites = $customer?->sites()->orderBy('name')->get() ?? collect();

        // Säkerställ att latest är uppdaterad även vid initial render
        $this->loadLatest($current);

        return view('livewire.dashboard.seo-health-card', [
            'sites' => $sites,
        ]);
    }
}
