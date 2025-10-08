<?php

namespace App\Livewire\SEO;

use App\Jobs\AnalyzeKeywordsJob;
use App\Jobs\FetchRankingsJob;
use App\Models\KeywordSuggestion;
use App\Services\Billing\PlanService;
use App\Support\CurrentCustomer;
use Illuminate\Support\Facades\Bus;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class KeywordSuggestionsIndex extends Component
{
    use WithPagination;

    public string $status = 'new';
    public ?string $q = null; // enkel sök
    public bool $isPremium = false;
    public bool $running = false;

    public function mount(CurrentCustomer $current, PlanService $plans): void
    {
        $customer = $current->get();
        if ($customer) {
            $sub = $plans->getSubscription($customer);
            $planId = (int)($plans->getPlanId($sub) ?? 0);
            $this->isPremium = in_array($planId, [2, 3], true);
        }
    }

    public function rerun(CurrentCustomer $current, PlanService $plans): void
    {
        if ($this->running) {
            return;
        }

        $customer = $current->get();
        $sub = $customer ? $plans->getSubscription($customer) : null;
        $planId = (int)($plans->getPlanId($sub) ?? 0);
        $isPremium = in_array($planId, [2, 3], true);

        if (!$isPremium) {
            session()->flash('error', 'Premium krävs för att köra om analysen.');
            return;
        }

        $siteId = $current->getSiteId();
        if (!$customer || !$siteId || !$customer->sites()->whereKey($siteId)->exists()) {
            session()->flash('error', 'Ingen giltig sajt vald.');
            return;
        }

        $this->running = true;

        // Kör kedjan; inget finally()-anrop här (dispatch returnerar ID)
        Bus::chain([
            (new FetchRankingsJob($siteId, 10))->onQueue('default'),
            (new AnalyzeKeywordsJob($siteId, 10))->onQueue('ai'),
        ])->dispatch();

        session()->flash('success', 'Analys påbörjad. Detta kan ta upp till några minuter.');
    }

    public function render(CurrentCustomer $current)
    {
        $customer = $current->get();
        abort_unless($customer, 403);

        $siteId = $current->getSiteId();
        abort_unless($siteId && $customer->sites()->whereKey($siteId)->exists(), 404, 'Ingen sajt vald.');

        $q = KeywordSuggestion::where('site_id', $siteId)
            ->when(!empty($this->q), function ($query) {
                $term = '%'.trim((string) $this->q).'%';
                $query->where(function ($sub) use ($term) {
                    $sub->where('url', 'like', $term)
                        ->orWhere('wp_type', 'like', $term)
                        ->orWhere('current', 'like', $term)
                        ->orWhere('suggested', 'like', $term)
                        ->orWhere('insights', 'like', $term);
                });
            })
            ->when($this->status !== 'all', fn($qq) => $qq->where('status', $this->status))
            ->latest();

        return view('livewire.seo.keyword-suggestions-index', [
            'rows' => $q->paginate(15),
            'isPremium' => $this->isPremium,
        ]);
    }
}
