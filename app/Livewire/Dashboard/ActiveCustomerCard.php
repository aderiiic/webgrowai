<?php

namespace App\Livewire\Dashboard;

use App\Models\UsageMetric;
use App\Support\CurrentCustomer;
use Livewire\Attributes\Layout;
use Livewire\Component;

class ActiveCustomerCard extends Component
{
    public int $monthGenerateTotal = 0;
    public int $monthPublishTotal = 0;
    public int $monthMailchimpTotal = 0;

    public function render(CurrentCustomer $current)
    {
        $customer = $current->get()?->loadMissing('sites');

        // Hämta månadsanvändning (ai.generate, ai.publish.wp)
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

            $this->monthMailchimpTotal = (int) (UsageMetric::where('customer_id', $customer->id)->where('period', $period)->where('metric_key', 'mailchimp.campaign')->value('used_value') ?? 0);
        } else {
            $this->monthGenerateTotal = $this->monthPublishTotal = $this->monthMailchimpTotal = 0;
        }

        return view('livewire.dashboard.active-customer-card', [
            'customer' => $customer,
            'sites' => $customer?->sites ?? collect(),
        ]);
    }
}
