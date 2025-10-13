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

        $activeSiteId = $current->getSiteId();

        $q = SeoAudit::query()
            ->whereIn('site_id', $customer->sites()->pluck('id'));

        // Om användaren har valt en aktiv sajt: visa endast dess audits
        if ($activeSiteId) {
            $q->where('site_id', (int) $activeSiteId);
        }

        $audits = $q->latest()->paginate(15);

        return view('livewire.seo.audit-history', [
            'audits' => $audits,
            'activeSiteId' => $activeSiteId,
        ]);
    }
}
