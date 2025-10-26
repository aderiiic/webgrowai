<?php

namespace App\Livewire\AI;

use App\Models\AiContent;
use App\Models\BulkGeneration;
use App\Support\CurrentCustomer;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class Index extends Component
{
    use WithPagination;

    public string $filterType = 'all'; // all, social, blog, seo, product, multi
    public bool $showArchived = false;
    public ?int $activeSiteId = null;

    public function mount(CurrentCustomer $current): void
    {
        $this->activeSiteId = $current->getSiteId();
    }

    #[On('active-site-updated')]
    public function onActiveSiteUpdated(?int $siteId): void
    {
        $this->activeSiteId = $siteId;
        $this->resetPage();
    }

    public function updatedFilterType(): void
    {
        $this->resetPage();
    }

    public function updatedShowArchived(): void
    {
        $this->resetPage();
    }

    public function toggleArchive(int $contentId): void
    {
        $customer = app(CurrentCustomer::class)->get();
        abort_unless($customer, 403);

        $content = AiContent::where('customer_id', $customer->id)
            ->findOrFail($contentId);

        $content->update(['archived' => !$content->archived]);

        $this->dispatch('content-archived');
    }

    public function render(CurrentCustomer $current): View
    {
        $customer = $current->get();
        abort_unless($customer, 403);

        $now = now();
        $startOfMonth = $now->copy()->startOfMonth();
        $period = $now->format('Y-m');
        $activeSiteId = $this->activeSiteId; // Spara i lokal variabel

        // Get regular AI contents - FILTRERA PÅ SITE
        $query = AiContent::where('customer_id', $customer->id)
            ->whereNull('bulk_generation_id') // Exclude bulk items from main list
            ->with(['template', 'site', 'publications']);

        // VIKTIGT: Filtrera på vald site
        if ($activeSiteId) {
            $query->where('site_id', $activeSiteId);
        }

        // Apply archive filter
        if ($this->showArchived) {
            $query->archived();
        } else {
            $query->active();
        }

        // Apply type filter
        if ($this->filterType !== 'all') {
            $query->where('type', $this->filterType);
        }

        $items = $query->orderBy('created_at', 'desc')->paginate(12);

        // Get bulk generations separately - OCKSÅ FILTRERA PÅ SITE
        $bulkQuery = BulkGeneration::where('customer_id', $customer->id)
            ->with(['contents' => function ($q) {
                $q->orderBy('batch_index')->limit(3);
            }])
            ->orderBy('created_at', 'desc')
            ->limit(10);

        if ($activeSiteId) {
            $bulkQuery->where('site_id', $activeSiteId);
        }

        $bulkGenerations = $bulkQuery->get();

        // Monthly stats - FILTRERA PÅ SITE för mer exakt statistik
        $monthGenerateQuery = DB::table('usage_metrics')
            ->where('customer_id', $customer->id)
            ->where('metric_key', 'ai.generate')
            ->where('period', $period);

        $monthPublishQuery = DB::table('usage_metrics')
            ->where('customer_id', $customer->id)
            ->where('metric_key', 'ai.publish')
            ->where('period', $period);

        // Om site är vald, räkna bara den sitens innehåll
        if ($activeSiteId) {
            // Räkna genererade texter för denna site
            $monthGenerateTotal = AiContent::where('customer_id', $customer->id)
                ->where('site_id', $activeSiteId)
                ->whereMonth('created_at', $now->month)
                ->whereYear('created_at', $now->year)
                ->count();

            $monthPublishTotal = DB::table('content_publications')
                ->whereIn('ai_content_id', function ($q) use ($customer, $activeSiteId) {
                    $q->select('id')
                        ->from('ai_contents')
                        ->where('customer_id', $customer->id)
                        ->where('site_id', $activeSiteId);
                })
                ->where('status', 'published')
                ->whereMonth('created_at', $now->month)
                ->whereYear('created_at', $now->year)
                ->count();
        } else {
            // Visa alla sites för kunden
            $monthGenerateTotal = $monthGenerateQuery->sum('used_value');
            $monthPublishTotal = $monthPublishQuery->sum('used_value');
        }

        $monthPublishBy = [
            'wp' => DB::table('content_publications')
                ->whereIn('ai_content_id', function ($q) use ($customer, $activeSiteId) {
                    $subQ = $q->select('id')->from('ai_contents')->where('customer_id', $customer->id);
                    if ($activeSiteId) {
                        $subQ->where('site_id', $activeSiteId);
                    }
                })
                ->where('target', 'wp')
                ->where('status', 'published')
                ->where('created_at', '>=', $startOfMonth)
                ->count(),
            'shopify' => DB::table('content_publications')
                ->whereIn('ai_content_id', function ($q) use ($customer, $activeSiteId) {
                    $subQ = $q->select('id')->from('ai_contents')->where('customer_id', $customer->id);
                    if ($activeSiteId) {
                        $subQ->where('site_id', $activeSiteId);
                    }
                })
                ->where('target', 'shopify')
                ->where('status', 'published')
                ->where('created_at', '>=', $startOfMonth)
                ->count(),
            'facebook' => DB::table('content_publications')
                ->whereIn('ai_content_id', function ($q) use ($customer, $activeSiteId) {
                    $subQ = $q->select('id')->from('ai_contents')->where('customer_id', $customer->id);
                    if ($activeSiteId) {
                        $subQ->where('site_id', $activeSiteId);
                    }
                })
                ->where('target', 'facebook')
                ->where('status', 'published')
                ->where('created_at', '>=', $startOfMonth)
                ->count(),
            'instagram' => DB::table('content_publications')
                ->whereIn('ai_content_id', function ($q) use ($customer, $activeSiteId) {
                    $subQ = $q->select('id')->from('ai_contents')->where('customer_id', $customer->id);
                    if ($activeSiteId) {
                        $subQ->where('site_id', $activeSiteId);
                    }
                })
                ->where('target', 'instagram')
                ->where('status', 'published')
                ->where('created_at', '>=', $startOfMonth)
                ->count(),
            'linkedin' => DB::table('content_publications')
                ->whereIn('ai_content_id', function ($q) use ($customer, $activeSiteId) {
                    $subQ = $q->select('id')->from('ai_contents')->where('customer_id', $customer->id);
                    if ($activeSiteId) {
                        $subQ->where('site_id', $activeSiteId);
                    }
                })
                ->where('target', 'linkedin')
                ->where('status', 'published')
                ->where('created_at', '>=', $startOfMonth)
                ->count(),
        ];

        return view('livewire.a-i.index', compact(
            'items',
            'bulkGenerations',
            'monthGenerateTotal',
            'monthPublishTotal',
            'monthPublishBy'
        ));
    }
}
