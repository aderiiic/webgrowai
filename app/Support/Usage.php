<?php

namespace App\Support;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Usage
{
    public function increment(int $customerId, string $key, ?string $period = null, int $by = 1): void
    {
        $period = $period ?: now()->format('Y-m');

        try {
            DB::statement(
                'INSERT INTO `usage_metrics` (`customer_id`, `period`, `metric_key`, `used_value`, `created_at`, `updated_at`)
                 VALUES (?, ?, ?, ?, NOW(), NOW())
                 ON DUPLICATE KEY UPDATE `used_value` = `used_value` + VALUES(`used_value`), `updated_at` = NOW()',
                [$customerId, $period, $key, $by]
            );
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
