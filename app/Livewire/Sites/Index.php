<?php

namespace App\Livewire\Sites;

use App\Models\SeoAudit;
use App\Support\CurrentCustomer;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class Index extends Component
{
    public array $latestBySite = [];

    public function mount(CurrentCustomer $current): void
    {
        $customer = $current->get();
        abort_unless($customer, 403);

        $siteIds = $customer->sites()->pluck('id');

        $latest = SeoAudit::whereIn('site_id', $siteIds)
            ->select('id','site_id','lighthouse_performance','created_at')
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy('site_id')
            ->map(fn($g) => optional($g->first())->toArray())
            ->toArray();

        $this->latestBySite = $latest;
    }

    public function render(CurrentCustomer $current)
    {
        $customer = $current->get();
        abort_unless($customer, 403);

        return view('livewire.sites.index', [
            'sites' => $customer->sites()->latest()->get(),
            'latestBySite' => $this->latestBySite,
        ]);
    }
}
