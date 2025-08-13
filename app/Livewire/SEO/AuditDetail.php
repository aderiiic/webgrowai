<?php

namespace App\Livewire\SEO;

use App\Models\SeoAudit;
use App\Support\CurrentCustomer;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class AuditDetail extends Component
{
    public SeoAudit $audit;
    public string $filter = 'all'; // all|title|meta|lighthouse

    public function mount(int $auditId, CurrentCustomer $current): void
    {
        $this->audit = SeoAudit::with('items', 'site')->findOrFail($auditId);

        $customer = $current->get();
        abort_unless($customer && $customer->sites()->whereKey($this->audit->site_id)->exists(), 403);
    }

    public function render()
    {
        $items = $this->audit->items()
            ->when($this->filter !== 'all', fn($q) => $q->where('type', $this->filter))
            ->latest()
            ->paginate(20);

        return view('livewire.seo.audit-detail', [
            'audit' => $this->audit,
            'items' => $items,
        ]);
    }
}
