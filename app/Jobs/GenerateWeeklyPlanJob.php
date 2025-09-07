<?php

namespace App\Jobs;

use App\Models\{AiContent, ContentTemplate, Customer};
use App\Services\AI\AiProviderManager;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class GenerateWeeklyPlanJob implements ShouldQueue
{
    use Queueable;

    public function __construct(public int $customerId) {
        $this->onQueue('ai');
    }

    public function handle(AiProviderManager $manager): void
    {
        $customer = Customer::findOrFail($this->customerId);

        $siteContexts = $customer->sites()->get()
            ->map(fn($site) => trim($site->aiContextSummary()))
            ->filter()
            ->values()
            ->all();

        $contextBlock = implode("\n", array_map(
            fn($c, $i) => "- Site ".($i+1).": ".$c,
            $siteContexts,
            array_keys($siteContexts)
        )) ?: null;

        $template = ContentTemplate::firstOrCreate(
            ['slug' => 'campaign'],
            ['name' => 'Kampanjidéer', 'provider' => 'openai', 'max_tokens' => 1000, 'temperature' => 0.6, 'visibility' => 'system']
        );

        $content = AiContent::create([
            'customer_id' => $customer->id,
            'site_id'     => null,
            'template_id' => $template->id,
            'title'       => 'Veckoplanering: kommande idéer',
            'tone'        => 'short',
            'status'      => 'queued',
            'inputs'      => [
                'audience' => $customer->weekly_audience ?: 'Befintliga och potentiella kunder',
                'goal'     => $customer->weekly_goal ?: 'Fler leads nästa vecka',
                'brand'    => ['name' => $customer->name, 'voice' => ($customer->weekly_brand_voice ?: 'hjälpsam, tydlig, handlingsinriktad')],
                'context'  => $contextBlock, // <-- injicera verksamhetskontekst
                'period'   => [
                    'week_start' => now()->startOfWeek()->format('Y-m-d'),
                    'week_end'   => now()->endOfWeek()->format('Y-m-d'),
                    'prev_week'  => [
                        'start' => now()->subWeek()->startOfWeek()->format('Y-m-d'),
                        'end'   => now()->subWeek()->endOfWeek()->format('Y-m-d'),
                    ],
                ],
            ],
        ]);

        dispatch(new GenerateContentJob($content->id))->onQueue('ai');
    }
}
