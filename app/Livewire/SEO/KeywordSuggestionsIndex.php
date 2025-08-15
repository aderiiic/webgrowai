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
        $siteIds = $customer->sites()->pluck('id');

        $q = KeywordSuggestion::whereIn('site_id', $siteIds)->latest();
        if ($this->status !== 'all') $q->where('status', $this->status);

        return view('livewire.seo.keyword-suggestions-index', [
            'rows' => $q->paginate(15),
        ]);
    }
}
