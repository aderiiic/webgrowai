<?php

namespace App\Livewire\Dashboard;

use App\Jobs\RunSeoAuditJob;
use App\Livewire\Dashboard\SeoHealthCard as Base;
use App\Models\SeoAudit;
use App\Models\UsageMetric;
use App\Support\CurrentCustomer;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;

class SeoHealthCard extends Component
{
    public ?SeoAudit $latest = null;
    public ?int $siteId = null;

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

        $query = SeoAudit::whereIn('site_id', $customer->sites()->pluck('id'));
        if ($this->siteId) {
            $query->where('site_id', $this->siteId);
        }
        $this->latest = $query->latest()->first();

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

    public function runAudit(CurrentCustomer $current, \App\Services\Billing\QuotaGuard $quota): void
    {
        $customer = $current->get();
        abort_unless($customer && $this->siteId, 403);

        try {
            // För‑kontrollera kvoten så vi kan visa feedback i UI
            $quota->checkOrFail($customer, 'seo.audit');
        } catch (\Throwable $e) {
            session()->flash('error', $e->getMessage());
            return;
        }

        dispatch((new RunSeoAuditJob($this->siteId))->onQueue('seo'));
        session()->flash('success', 'SEO audit startad för vald sajt.');
    }

    public function runAuditAll(CurrentCustomer $current, \App\Services\Billing\QuotaGuard $quota): void
    {
        $customer = $current->get();
        abort_unless($customer, 403);

        try {
            $quota->checkOrFail($customer, 'seo.audit');
        } catch (\Throwable $e) {
            session()->flash('error', $e->getMessage());
            return;
        }

        $ids = $customer->sites()->pluck('id');
        foreach ($ids as $id) {
            // För varje audit dras kvot i jobbet (och QuotaGuard i jobbet stoppar ev. fortsättning),
            // men vi kör en initial check här för att ge direkt feedback.
            dispatch((new RunSeoAuditJob($id))->onQueue('seo'));
        }
        session()->flash('success', 'SEO audits startade för alla sajter.');
    }

    public function render(CurrentCustomer $current)
    {
        $customer = $current->get();
        $sites = $customer?->sites()->orderBy('name')->get() ?? collect();

        $this->loadLatest($current);

        return view('livewire.dashboard.seo-health-card', [
            'sites' => $sites,
        ]);
    }
}
