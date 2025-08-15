<?php

namespace App\Livewire\SEO;

use App\Models\SeoAudit;
use App\Support\CurrentCustomer;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class AuditHistory extends Component
{
    public function render(CurrentCustomer $current)
    {
        $customer = $current->get();
        abort_unless($customer, 403);

        $audits = SeoAudit::whereIn('site_id', $customer->sites()->pluck('id'))
            ->latest()
            ->paginate(15);

        return view('livewire.seo.audit-history', compact('audits'));
    }
}
