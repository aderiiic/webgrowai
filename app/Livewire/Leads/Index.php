<?php

namespace App\Livewire\Leads;

use App\Models\Lead;
use App\Models\LeadScore;
use App\Support\CurrentCustomer;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class Index extends Component
{
    use WithPagination;

    public ?int $siteId = null;
    public ?int $minScore = 0;
    public string $period = '30d'; // 24h|7d|30d|all

    public function render(CurrentCustomer $current)
    {
        $customer = $current->get();
        abort_unless($customer, 403);

        $sites = $customer->sites()->orderBy('name')->get();

        $leadIds = Lead::whereIn('site_id', $sites->pluck('id'))
            ->when($this->siteId, fn($q) => $q->where('site_id', $this->siteId))
            ->pluck('id');

        $scores = LeadScore::with(['lead' => fn($q) => $q->select('id','site_id','email','visitor_id','last_seen','sessions')])
            ->whereIn('lead_id', $leadIds)
            ->when($this->minScore, fn($q) => $q->where('score_norm', '>=', $this->minScore))
            ->orderByDesc('score_norm')
            ->paginate(20);

        return view('livewire.leads.index', [
            'sites' => $sites,
            'scores' => $scores,
        ]);
    }
}
