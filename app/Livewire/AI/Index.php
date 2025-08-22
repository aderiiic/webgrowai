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

    // Uppdelning per kanal
    public array $monthPublishBy = [
        'wp'        => 0,
        'shopify'   => 0,
        'facebook'  => 0,
        'instagram' => 0,
        'linkedin'  => 0,
    ];

    public function render(CurrentCustomer $current)
    {
        $customer = $current->get();

        $items = AiContent::with('publications')
            ->where('customer_id', $customer?->id)
            ->latest()
            ->paginate(12);

        if ($customer) {
            $period = now()->format('Y-m');

            $this->monthGenerateTotal = (int) (UsageMetric::query()
                ->where('customer_id', $customer->id)
                ->where('period', $period)
                ->where('metric_key', 'ai.generate')
                ->value('used_value') ?? 0);

            // Total publiceringar (alla kanaler)
            $this->monthPublishTotal = (int) (UsageMetric::query()
                ->where('customer_id', $customer->id)
                ->where('period', $period)
                ->where('metric_key', 'like', 'ai.publish.%')
                ->sum('used_value') ?? 0);

            // Uppdelning per kanal
            $this->monthPublishBy['wp']        = (int) (UsageMetric::query()->where('customer_id', $customer->id)->where('period', $period)->where('metric_key', 'ai.publish.wp')->value('used_value') ?? 0);
            $this->monthPublishBy['shopify']   = (int) (UsageMetric::query()->where('customer_id', $customer->id)->where('period', $period)->where('metric_key', 'ai.publish.shopify')->value('used_value') ?? 0);
            $this->monthPublishBy['facebook']  = (int) (UsageMetric::query()->where('customer_id', $customer->id)->where('period', $period)->where('metric_key', 'ai.publish.facebook')->value('used_value') ?? 0);
            $this->monthPublishBy['instagram'] = (int) (UsageMetric::query()->where('customer_id', $customer->id)->where('period', $period)->where('metric_key', 'ai.publish.instagram')->value('used_value') ?? 0);
            $this->monthPublishBy['linkedin']  = (int) (UsageMetric::query()->where('customer_id', $customer->id)->where('period', $period)->where('metric_key', 'ai.publish.linkedin')->value('used_value') ?? 0);
        } else {
            $this->monthGenerateTotal = 0;
            $this->monthPublishTotal  = 0;
            $this->monthPublishBy     = ['wp'=>0,'shopify'=>0,'facebook'=>0,'instagram'=>0,'linkedin'=>0];
        }

        return view('livewire.a-i.index', [
            'items'             => $items,
            'monthGenerateTotal'=> $this->monthGenerateTotal,
            'monthPublishTotal' => $this->monthPublishTotal,
            'monthPublishBy'    => $this->monthPublishBy,
        ]);
    }
}
