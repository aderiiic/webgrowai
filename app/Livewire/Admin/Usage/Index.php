<?php

namespace App\Livewire\Admin\Usage;

use App\Models\Customer;
use App\Models\UsageMetric;
use Illuminate\Support\Collection;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class Index extends Component
{
    use WithPagination;

    public ?int $customerId = null;
    public string $period = '';
    public string $searchKey = '';
    public int $perPage = 25;

    public function mount(): void
    {
        $this->period = now()->format('Y-m');
    }

    public function updating($field): void
    {
        if (in_array($field, ['customerId','period','searchKey','perPage'], true)) {
            $this->resetPage();
        }
    }

    public function getCustomersProperty(): Collection
    {
        return Customer::orderBy('name')->get();
    }

    public function getSummaryProperty(): array
    {
        $query = UsageMetric::query();

        if ($this->customerId) {
            $query->where('customer_id', $this->customerId);
        }
        if ($this->period !== '') {
            $query->where('period', $this->period);
        }
        if ($this->searchKey !== '') {
            $query->where('metric_key', 'like', '%'.$this->searchKey.'%');
        }

        $rows = $query->selectRaw('metric_key, SUM(used_value) as total')->groupBy('metric_key')->get();

        return [
            'total_by_key' => $rows->mapWithKeys(fn($r) => [$r->metric_key => (int)$r->total])->toArray(),
            'grand_total'  => (int) $rows->sum('total'),
        ];
    }

    public function exportCsv(): \Symfony\Component\HttpFoundation\StreamedResponse
    {
        $fileName = 'usage_export_'.($this->period ?: 'all').'.csv';

        $query = UsageMetric::query()
            ->with('customer:id,name')
            ->orderBy('period', 'desc')
            ->orderBy('customer_id')
            ->orderBy('metric_key');

        if ($this->customerId) {
            $query->where('customer_id', $this->customerId);
        }
        if ($this->period !== '') {
            $query->where('period', $this->period);
        }
        if ($this->searchKey !== '') {
            $query->where('metric_key', 'like', '%'.$this->searchKey.'%');
        }

        return response()->streamDownload(function () use ($query) {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['Customer', 'Period', 'Key', 'Used']);

            $query->chunk(500, function ($chunk) use ($out) {
                foreach ($chunk as $row) {
                    fputcsv($out, [
                        $row->customer?->name ?? ('#'.$row->customer_id),
                        $row->period,
                        $row->metric_key,
                        (int) $row->used_value,
                    ]);
                }
            });

            fclose($out);
        }, $fileName, ['Content-Type' => 'text/csv']);
    }

    public function render()
    {
        $query = UsageMetric::query()
            ->with('customer:id,name')
            ->orderBy('period', 'desc')
            ->orderBy('customer_id')
            ->orderBy('metric_key');

        if ($this->customerId) {
            $query->where('customer_id', $this->customerId);
        }
        if ($this->period !== '') {
            $query->where('period', $this->period);
        }
        if ($this->searchKey !== '') {
            $query->where('metric_key', 'like', '%'.$this->searchKey.'%');
        }

        $metrics = $query->paginate($this->perPage);

        return view('livewire.admin.usage.index', [
            'metrics'   => $metrics,
            'customers' => $this->customers,
            'summary'   => $this->summary,
        ]);
    }
}
