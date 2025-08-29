<?php

namespace App\Livewire\CRO;

use App\Models\ConversionSuggestion;
use App\Support\CurrentCustomer;
use Livewire\Component;
use Livewire\WithPagination;

#[\Livewire\Attributes\Layout('layouts.app')]
class SuggestionIndex extends Component
{
    use WithPagination;

    public string $status = 'new'; // new|applied|dismissed|all

    public function render(CurrentCustomer $current)
    {
        $customer = $current->get();
        abort_unless($customer, 403);

        $siteId = $current->getSiteId();
        // Säkerställ att vald sajt tillhör aktiva kunden
        abort_unless($siteId && $customer->sites()->whereKey($siteId)->exists(), 404, 'Ingen sajt vald.');

        $q = ConversionSuggestion::where('site_id', $siteId)->latest();
        if ($this->status !== 'all') {
            $q->where('status', $this->status);
        }

        return view('livewire.cro.suggestion-index', [
            'sugs' => $q->paginate(15),
        ]);
    }
}
