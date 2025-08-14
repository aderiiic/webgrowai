<?php

namespace App\Livewire\AI;

use App\Models\AiContent;
use App\Models\UsageMetric;
use App\Support\CurrentCustomer;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class Index extends Component
{
    use WithPagination;

    public int $monthGenerateTotal = 0;
    public int $monthPublishTotal = 0;

    public function render(CurrentCustomer $current)
    {
        $customer = $current->get();

        $items = AiContent::where('customer_id', $customer?->id)
            ->latest()
            ->paginate(12);

        if ($customer) {
            $period = now()->format('Y-m');
            $this->monthGenerateTotal = (int) (UsageMetric::query()
                ->where('customer_id', $customer->id)
                ->where('period', $period)
                ->where('metric_key', 'ai.generate')
                ->value('used_value') ?? 0);

            $this->monthPublishTotal = (int) (UsageMetric::query()
                ->where('customer_id', $customer->id)
                ->where('period', $period)
                ->where('metric_key', 'ai.publish.wp')
                ->value('used_value') ?? 0);
        } else {
            $this->monthGenerateTotal = 0;
            $this->monthPublishTotal = 0;
        }

        return view('livewire.ai.index', [
            'items' => $items,
            'monthGenerateTotal' => $this->monthGenerateTotal,
            'monthPublishTotal'  => $this->monthPublishTotal,
        ]);
    }
}
