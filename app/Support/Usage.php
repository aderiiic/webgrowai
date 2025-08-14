<?php

namespace App\Support;

use App\Models\UsageMetric;
use Illuminate\Support\Facades\Log;

class Usage
{
    public function increment(int $customerId, string $key, ?string $period = null, int $by = 1): void
    {
        $period = $period ?: now()->format('Y-m');

        try {
            $metric = UsageMetric::firstOrCreate([
                'customer_id' => $customerId,
                'period'      => $period,
                'metric_key'  => $key,
            ], [
                'used_value'  => 0,
            ]);

            $metric->increment('used_value', $by);
        } catch (\Throwable $e) {
            Log::error('[Usage] increment failed', [
                'customer_id' => $customerId,
                'period' => $period,
                'metric_key' => $key,
                'by' => $by,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
