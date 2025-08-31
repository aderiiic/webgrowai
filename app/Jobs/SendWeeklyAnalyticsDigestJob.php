<?php
// app/Jobs/SendWeeklyAnalyticsDigestJob.php

namespace App\Jobs;

use App\Mail\WeeklyAnalyticsDigestMail;
use App\Models\Customer;
use App\Services\Analytics\AnalyticsService;
use App\Services\Billing\PlanService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendWeeklyAnalyticsDigestJob implements ShouldQueue
{
    use Queueable, InteractsWithQueue;

    public function __construct(public int $customerId) {}

    public function handle(AnalyticsService $analytics, PlanService $plans): void
    {
        $customer = Customer::find($this->customerId);
        if (!$customer) return;

        // Tolka som aktiv om kvoten inte är null
        if ($plans->getQuota($customer, 'digest.weekly') === null) {
            Log::info('[Digest] Avstängd för kund', ['customer_id' => $this->customerId]);
            return;
        }

        $sites = $customer->sites()->select('id','name')->get();

        $payload = [
            'customerName' => $customer->name,
            'period'       => now()->subWeek()->startOfWeek()->format('Y-m-d') . ' – ' . now()->subWeek()->endOfWeek()->format('Y-m-d'),
            'sites'        => [],
        ];

        foreach ($sites as $site) {
            $siteId = (int) $site->id;
            $payload['sites'][] = [
                'site' => ['id' => $siteId, 'name' => $site->name],
                'website' => $analytics->getWebsiteMetrics($customer->id, $siteId),
                'publications' => $analytics->getPublicationsMetrics($customer->id, $siteId),
                'social' => $analytics->getSocialMetrics($customer->id, $siteId),
                'insights' => $analytics->getInsightsSummary($customer->id, $siteId),
            ];
        }

        $to = $customer->email ?? 'info+customer@example.com';

        Mail::to($to)->send(new WeeklyAnalyticsDigestMail($payload));
    }
}
