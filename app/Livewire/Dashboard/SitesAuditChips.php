<?php

namespace App\Livewire\Dashboard;

use App\Jobs\RunSeoAuditJob;
use App\Models\SeoAudit;
use App\Models\Site;
use App\Support\CurrentCustomer;
use Illuminate\Support\Collection;
use Livewire\Attributes\Layout;
use Livewire\Component;

class SitesAuditChips extends Component
{
    /** @var Collection<int, Site> */
    public Collection $sites;

    /** @var array<int, array{id:int,site_id:int,lighthouse_performance:?int,created_at:string}> */
    public array $latestBySite = [];

    public function mount(CurrentCustomer $current): void
    {
        $customer = $current->get();
        abort_unless($customer, 403);

        $this->sites = $customer->sites()->orderBy('name')->get();

        $this->refreshLatest();
    }

    public function refreshLatest(): void
    {
        if ($this->sites->isEmpty()) {
            $this->latestBySite = [];
            return;
        }

        $siteIds = $this->sites->pluck('id');

        $latest = SeoAudit::whereIn('site_id', $siteIds)
            ->select('id','site_id','lighthouse_performance','created_at')
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy('site_id')
            ->map(fn($g) => optional($g->first())->toArray())
            ->toArray();

        $this->latestBySite = $latest;
    }

    public function runAudit(int $siteId): void
    {
        // Säkerställ att site tillhör listan
        if (! $this->sites->pluck('id')->contains($siteId)) {
            abort(403);
        }
        dispatch((new RunSeoAuditJob($siteId))->onQueue('seo'));
        session()->flash('success', 'SEO audit startad.');
    }

    public function performanceColor(?int $score): string
    {
        if ($score === null) return 'bg-gray-100 text-gray-700 border-gray-200';
        if ($score >= 90) return 'bg-emerald-50 text-emerald-700 border-emerald-200';
        if ($score >= 70) return 'bg-amber-50 text-amber-700 border-amber-200';
        return 'bg-rose-50 text-rose-700 border-rose-200';
    }

    public function render()
    {
        return view('livewire.dashboard.sites-audit-chips');
    }
}
