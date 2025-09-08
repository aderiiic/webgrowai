<?php

namespace App\Jobs;

use App\Models\{AiContent, ContentTemplate, Customer, Site};
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
        $site = Site::with('customer')->findOrFail($this->siteId);
        $customer = $site->customer;

        $template = ContentTemplate::firstOrCreate(
            ['slug' => 'campaign'],
            ['name' => 'Kampanjidéer', 'provider' => 'openai', 'max_tokens' => 1000, 'temperature' => 0.6, 'visibility' => 'system']
        );

        $audience = $site->weekly_audience ?: ($customer->weekly_audience ?: 'Befintliga och potentiella kunder');
        $goal     = $site->weekly_goal     ?: ($customer->weekly_goal     ?: 'Fler leads nästa vecka');
        $voice    = $site->weekly_brand_voice ?: ($customer->weekly_brand_voice ?: 'hjälpsam, tydlig, handlingsinriktad');

        // Kontekst: strikt site-fokuserad (vill du ha multi-site-kontekst, bygg en block som i digest-jobbet)
        $contextBlock = trim($site->aiContextSummary()) ?: null;

        $content = AiContent::create([
            'customer_id' => $customer->id,
            'site_id'     => $site->id,
            'template_id' => $template->id,
            'title'       => 'Veckoplanering: kommande idéer',
            'tone'        => 'short',
            'status'      => 'queued',
            'inputs'      => [
                'audience' => $audience,
                'goal'     => $goal,
                'brand'    => ['name' => $customer->name, 'voice' => $voice],
                'context'  => $contextBlock,
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
