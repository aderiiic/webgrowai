<?php

namespace App\Livewire\Dashboard;

use App\Models\SeoAudit;
use App\Support\CurrentCustomer;
use Livewire\Component;

class SeoHealthCard extends Component
{
    public ?SeoAudit $latest = null;

    public function render(CurrentCustomer $current)
    {
        $customer = $current->get();
        if ($customer) {
            $siteIds = $customer->sites()->pluck('id');
            $this->latest = SeoAudit::whereIn('site_id', $siteIds)->latest()->first();
        }

        return view('livewire.dashboard.seo-health-card');
    }
}
