<?php

namespace App\Livewire\Dashboard;

use App\Jobs\RunSeoAuditJob;
use App\Models\SeoAudit;
use App\Models\UsageMetric;
use App\Support\CurrentCustomer;
use Livewire\Component;

class SeoHealthCard extends Component
{
    public ?SeoAudit $latest = null;
    public ?int $siteId = null; // vald site

    // Månadsbadges (kundnivå)
    public int $monthGenerateTotal = 0;
    public int $monthPublishTotal  = 0;
    public int $monthAuditTotal    = 0;

    public function mount(CurrentCustomer $current): void
    {
        $customer = $current->get();
        if ($customer) {
            $this->siteId = $customer->sites()->orderBy('id')->value('id');
        }
    }

    public function updatedSiteId(CurrentCustomer $current): void
    {
        $this->loadLatest($current);
    }

    public function loadLatest(CurrentCustomer $current): void
    {
        $customer = $current->get();
        if (!$customer) {
            $this->latest = null;
            $this->monthGenerateTotal = 0;
            $this->monthPublishTotal  = 0;
            $this->monthAuditTotal    = 0;
            return;
        }

        // Senaste audit (för vald site om satt, annars senaste för kundens sajter)
        $query = SeoAudit::whereIn('site_id', $customer->sites()->pluck('id'));
        if ($this->siteId) {
            $query->where('site_id', $this->siteId);
        }
        $this->latest = $query->latest()->first();

        // Månadsbadges (kundnivå)
        $period = now()->format('Y-m');
        $this->monthGenerateTotal = (int) (UsageMetric::query()
            ->where('customer_id', $customer->id)
            ->where('period', $period)
            ->where('metric_key', 'ai.generate')
            ->value('used_value') ?? 0);

        $this->monthPublishTotal = (int) (UsageMetric::query()
            ->where('customer_id', $customer->id)
            ->where('period', $period)
            ->where('metric_key', 'ai.publish.wp')
            ->value('used_value') ?? 0);

        $this->monthAuditTotal = (int) (UsageMetric::query()
            ->where('customer_id', $customer->id)
            ->where('period', $period)
            ->where('metric_key', 'seo.audit')
            ->value('used_value') ?? 0);
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

        // Ladda data vid render också (så badges uppdateras)
        $this->loadLatest($current);

        return view('livewire.dashboard.seo-health-card', [
            'sites' => $sites,
        ]);
    }
}
