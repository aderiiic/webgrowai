<?php

namespace App\Services\Billing;

use App\Models\Customer;
use Illuminate\Support\Facades\DB;
use App\Support\Usage;

class QuotaGuard
{
    public function __construct(
        private PlanService $plans,
        private Usage $usage
    ) {}

    /**
     * Legacy-funktion för feature-nycklar (behålls för bakåtkomp).
     * @throws \RuntimeException
     */
    public function checkOrFail(Customer $customer, string $metricKey): void
    {
        if (!$this->plans->hasAccess($customer)) {
            throw new \RuntimeException('Kontot är pausat. Välj en plan för att fortsätta.');
        }

        $quota = $this->plans->getQuota($customer, $metricKey);
        if ($quota === null) return; // ej kvoterad/obegränsad

        $period = now()->format('Y-m');
        $used = (int) (DB::table('usage_metrics')
            ->where('customer_id', $customer->id)
            ->where('period', $period)
            ->where('metric_key', $metricKey)
            ->value('used_value') ?? 0);

        if ($used >= $quota) {
            throw new \RuntimeException('Kvotgräns uppnådd. Uppgradera plan eller godkänn extraanvändning.');
        }
    }

    /**
     * Kreditbaserad kontroll före åtgärd.
     * @throws \RuntimeException
     */
    public function checkCreditsOrFail(Customer $customer, int $costCredits, string $usageKeyBase = 'credits'): void
    {
        if (!$this->plans->hasAccess($customer)) {
            throw new \RuntimeException('Kontot är pausat. Välj en plan för att fortsätta.');
        }

        $period = now()->format('Y-m');

        // Månadsbudget
        $monthCap = $this->plans->getQuota($customer, 'credits.monthly'); // null = obegränsat
        $usedMonth = (int) (DB::table('usage_metrics')
            ->where('customer_id', $customer->id)
            ->where('period', $period)
            ->where('metric_key', 'credits.used')
            ->value('used_value') ?? 0);

        if (!is_null($monthCap) && $usedMonth + $costCredits > $monthCap) {
            throw new \RuntimeException('Du har inte tillräckligt med kredit. Uppgradera för mer kapacitet.');
        }

        // Daglig cap (mjuk)
        $dayCap = $this->plans->getQuota($customer, 'credits.daily_cap'); // null = obegränsat
        $usedDay = $this->countToday($customer->id, $usageKeyBase);
        if (!is_null($dayCap) && $usedDay + $costCredits > $dayCap) {
            throw new \RuntimeException('Du har nått din dagliga limit. Uppgradera till högre plan för mer kapacitet.');
        }

        // Timvis cap (mjuk)
        $hourCap = $this->plans->getQuota($customer, 'credits.hourly_cap'); // null = obegränsat
        $usedHour = $this->countThisHour($customer->id, $usageKeyBase);
        if (!is_null($hourCap) && $usedHour + $costCredits > $hourCap) {
            throw new \RuntimeException('Du har nått din timvisa limit. Försök senare eller uppgradera plan.');
        }
    }

    /**
     * Debitera krediter efter lyckad åtgärd.
     */
    public function chargeCredits(Customer $customer, int $costCredits, string $usageKeyBase = 'credits'): void
    {
        $period = now()->format('Y-m');
        DB::transaction(function () use ($customer, $costCredits, $period, $usageKeyBase) {
            DB::statement(
                'INSERT INTO `usage_metrics` (`customer_id`, `period`, `metric_key`, `used_value`, `created_at`, `updated_at`)
                 VALUES (?, ?, ?, ?, NOW(), NOW())
                 ON DUPLICATE KEY UPDATE `used_value` = `used_value` + VALUES(`used_value`), `updated_at` = NOW()',
                [$customer->id, $period, 'credits.used', $costCredits]
            );

            $this->incrementToday($customer->id, $usageKeyBase, $costCredits);
            $this->incrementThisHour($customer->id, $usageKeyBase, $costCredits);
        });
    }

    // Hjälp för dag/timme (perioden i tabellen är fortfarande månadsbucket)
    private function countToday(int $customerId, string $keyBase): int
    {
        $period = now()->format('Y-m');
        $key = $keyBase . '.day.' . now()->format('Y-m-d');

        return (int) (DB::table('usage_metrics')
            ->where('customer_id', $customerId)
            ->where('period', $period)
            ->where('metric_key', $key)
            ->value('used_value') ?? 0);
    }

    private function countThisHour(int $customerId, string $keyBase): int
    {
        $period = now()->format('Y-m');
        $key = $keyBase . '.hour.' . now()->format('Y-m-d-H');

        return (int) (DB::table('usage_metrics')
            ->where('customer_id', $customerId)
            ->where('period', $period)
            ->where('metric_key', $key)
            ->value('used_value') ?? 0);
    }

    private function incrementToday(int $customerId, string $keyBase, int $by): void
    {
        $this->usage->increment($customerId, $keyBase . '.day.' . now()->format('Y-m-d'), now()->format('Y-m'), $by);
    }

    private function incrementThisHour(int $customerId, string $keyBase, int $by): void
    {
        $this->usage->increment($customerId, $keyBase . '.hour.' . now()->format('Y-m-d-H'), now()->format('Y-m'), $by);
    }
}
