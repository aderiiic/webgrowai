<?php
// app/Livewire/Analytics/Overview.php

namespace App\Livewire\Analytics;

use App\Services\Analytics\AnalyticsService;
use App\Services\Billing\PlanService;
use App\Support\CurrentCustomer;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class Overview extends Component
{
    public ?int $activeSiteId = null;

    public function render(CurrentCustomer $current, AnalyticsService $analytics, PlanService $plans): View
    {
        $customer = $current->get();
        abort_unless($customer, 403);

        $this->activeSiteId = (int) ($current->getSiteId() ?: 0) ?: null;

        // Tolka feature som aktiv om kvoten inte är null (boolean-features: limit_value=1)
        $cap = (object) [
            'basic'    => $plans->getQuota($customer, 'analytics.basic') !== null,
            'advanced' => $plans->getQuota($customer, 'analytics.advanced') !== null,
            'digest'   => $plans->getQuota($customer, 'digest.weekly') !== null,
        ];

        $website = $analytics->getWebsiteMetrics($customer->id, $this->activeSiteId);
        $publications = $analytics->getPublicationsMetrics($customer->id, $this->activeSiteId);
        $social = $analytics->getSocialMetrics($customer->id, $this->activeSiteId);

        $advanced = [
            'topContent'       => $cap->advanced ? $analytics->getTopContent($customer->id, $this->activeSiteId) : [],
            'bestPostTimes'    => $cap->advanced ? $analytics->getBestPostTimes($customer->id, $this->activeSiteId) : [],
            'engagementTrends' => $cap->advanced ? $analytics->getEngagementTrend($customer->id, $this->activeSiteId) : [],
            'siteCompare'      => $cap->advanced && !$this->activeSiteId ? $analytics->compareSites($customer->id) : [],
            'insights'         => $cap->advanced ? $analytics->getInsightsSummary($customer->id, $this->activeSiteId) : null,
        ];

        return view('livewire.analytics.overview', compact('cap', 'website', 'publications', 'social', 'advanced'))
            ->with('activeSiteId', $this->activeSiteId);
    }
}
