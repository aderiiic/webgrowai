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
            [
                'name'         => 'Kampanjidéer',
                'provider'     => 'openai',
                'max_tokens'   => 1000,
                'temperature'  => 0.6,
                'visibility'   => 'system',
            ]
        );

        $voice    = $site->effectiveBrandVoice() ?? 'hjälpsam, tydlig, handlingsinriktad';
        $audience = $site->effectiveAudience()    ?? 'Befintliga och potentiella kunder';
        $goal     = $site->effectiveGoal()        ?? 'Fler leads nästa vecka';
        $keywords = $site->effectiveKeywords();

        $contextBlock = trim($site->aiContextSummary()) ?: null;

        $now          = now();
        $currentYear  = (int) $now->year;
        $allowedYears = [$currentYear, $currentYear + 1];

        $content = AiContent::create([
            'customer_id' => $customer->id,
            'site_id'     => $site->id,
            'template_id' => $template->id,
            'title'       => 'Veckoplanering: kommande idéer',
            'tone'        => 'short',
            'status'      => 'queued',
            'inputs'      => [
                'brand'          => ['name' => $customer->name, 'voice' => $voice],
                'audience'       => $audience,
                'goal'           => $goal,
                'keywords'       => $keywords,
                'context'        => $contextBlock,

                // Nytt: tydlig tids- och lokaliseringssignalering
                'now_iso'        => $now->toIso8601String(),
                'current_year'   => $currentYear,
                'allowed_years'  => $allowedYears,
                'timezone'       => config('app.timezone'),
                'locale'         => $site->locale ?? config('app.locale', 'sv_SE'),

                // Veckoperiod (förstärker att vi är i aktuell vecka)
                'period'   => [
                    'week_start' => $now->startOfWeek()->format('Y-m-d'),
                    'week_end'   => $now->endOfWeek()->format('Y-m-d'),
                    'prev_week'  => [
                        'start' => $now->copy()->subWeek()->startOfWeek()->format('Y-m-d'),
                        'end'   => $now->copy()->subWeek()->endOfWeek()->format('Y-m-d'),
                    ],
                ],

                // Extra policy-hint för modellen att undvika gamla årtal i rubriker
                'date_guardrails' => [
                    'avoid_past_years_in_titles' => true,
                    'replace_generic_past_years_with' => $currentYear,
                ],
            ],
        ]);

        dispatch(new GenerateContentJob($content->id))->onQueue('ai');
    }
}
