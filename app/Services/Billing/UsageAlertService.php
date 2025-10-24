<?php

namespace App\Services\Billing;

use App\Mail\UsageThresholdMail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class UsageAlertService
{
    // Endast credits + ev. sites (hård)
    private array $metrics = [
        'credits.used' => 'Krediter denna månad',
    ];

    public function run(): void
    {
        $period = now()->format('Y-m');

        $customers = DB::table('subscriptions')
            ->join('customers','customers.id','=','subscriptions.customer_id')
            ->select('customers.*','subscriptions.plan_id')
            ->get();

        foreach ($customers as $c) {
            foreach ($this->metrics as $key => $label) {
                if ($key === 'credits.used') {
                    $quota = DB::table('plan_features')
                        ->where('plan_id', $c->plan_id)
                        ->where('key', 'credits.monthly')
                        ->where('is_enabled', true)
                        ->value('limit_value');

                    if (!is_numeric($quota)) continue;
                    $quota = (int)$quota;

                    $used = (int) (DB::table('usage_metrics')
                        ->where('customer_id', $c->id)
                        ->where('period', $period)
                        ->where('metric_key', 'credits.used')
                        ->value('used_value') ?? 0);

                    if ($quota <= 0) continue;
                    $pct = ($used / $quota) * 100;

                    $level = null;
                    if ($pct >= 100) $level = 'stop';
                    else if ($pct >= 80) $level = 'warn';

                    if (!$level) continue;

                    $sent = DB::table('usage_alerts')
                        ->where('customer_id', $c->id)
                        ->where('period', $period)
                        ->where('metric_key', $key)
                        ->where('level', $level)
                        ->exists();

                    if ($sent) continue;

                    $to = $c->billing_email ?: $c->contact_email;
                    if (!$to) continue;

                    Mail::to($to)->send(new UsageThresholdMail(
                        $c->name,
                        $label,
                        $used,
                        $quota,
                        $level
                    ));

                    DB::table('usage_alerts')->insert([
                        'customer_id' => $c->id,
                        'period' => $period,
                        'metric_key' => $key,
                        'level' => $level,
                        'sent_at' => now(),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }
    }
}
