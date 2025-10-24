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

    public function countThisMonth(int $customerId, string $key, ?string $period = null): int
    {
        $period = $period ?: now()->format('Y-m');

        try {
            return (int) DB::table('usage_metrics')
                ->where('customer_id', $customerId)
                ->where('period', $period)
                ->where('metric_key', $key)
                ->sum('used_value');
        } catch (\Throwable $e) {
            Log::error('[Usage] countThisMonth failed', [
                'customer_id' => $customerId,
                'period'      => $period,
                'metric_key'  => $key,
                'error'       => $e->getMessage(),
            ]);
            return 0;
        }
    }

    // Hjälpare för dag/timme anropad av QuotaGuard
    public function countToday(int $customerId, string $keyBase): int
    {
        $period = now()->format('Y-m');
        $key = $keyBase . '.day.' . now()->format('Y-m-d');

        try {
            return (int) DB::table('usage_metrics')
                ->where('customer_id', $customerId)
                ->where('period', $period)
                ->where('metric_key', $key)
                ->sum('used_value');
        } catch (\Throwable $e) {
            return 0;
        }
    }

    public function countThisHour(int $customerId, string $keyBase): int
    {
        $period = now()->format('Y-m');
        $key = $keyBase . '.hour.' . now()->format('Y-m-d-H');

        try {
            return (int) DB::table('usage_metrics')
                ->where('customer_id', $customerId)
                ->where('period', $period)
                ->where('metric_key', $key)
                ->sum('used_value');
        } catch (\Throwable $e) {
            return 0;
        }
    }
}
