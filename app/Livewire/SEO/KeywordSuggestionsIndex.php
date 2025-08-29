<?php

namespace App\Livewire\SEO;

use App\Models\KeywordSuggestion;
use App\Support\CurrentCustomer;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class KeywordSuggestionsIndex extends Component
{
    use WithPagination;

    public string $status = 'new';

    public function render(CurrentCustomer $current)
    {
        $customer = $current->get();
        abort_unless($customer, 403);

        // Filtrera på aktiv (vald) sajt
        $siteId = $current->getSiteId();
        abort_unless($siteId && $customer->sites()->whereKey($siteId)->exists(), 404, 'Ingen sajt vald.');

        $q = KeywordSuggestion::where('site_id', $siteId)->latest();
        if ($this->status !== 'all') {
            $q->where('status', $this->status);
        }

        return view('livewire.seo.keyword-suggestions-index', [
            'rows' => $q->paginate(15),
        ]);
    }
}
