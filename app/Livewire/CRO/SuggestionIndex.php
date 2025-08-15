<?php

namespace App\Livewire\CRO;

use App\Models\ConversionSuggestion;
use App\Support\CurrentCustomer;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class SuggestionIndex extends Component
{
    use WithPagination;

    public string $status = 'new'; // new|applied|dismissed|all

    public function render(CurrentCustomer $current)
    {
        $customer = $current->get();
        abort_unless($customer, 403);

        $siteIds = $customer->sites()->pluck('id');

        $q = ConversionSuggestion::whereIn('site_id', $siteIds)->latest();
        if ($this->status !== 'all') $q->where('status', $this->status);

        return view('livewire.cro.suggestion-index', [
            'sugs' => $q->paginate(15),
        ]);
    }
}
