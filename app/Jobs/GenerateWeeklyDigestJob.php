<?php

namespace App\Jobs;

use App\Mail\WeeklyDigestMail;
use App\Models\Customer;
use App\Models\Site;
use App\Models\WeeklyPlan;
use App\Services\AI\OpenAIProvider;
use App\Support\Usage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;

class GenerateWeeklyDigestJob implements ShouldQueue
{
    use Queueable;

    public function __construct(public int $siteId, public string $runTag = 'monday') // monday|friday
    {
        $this->onQueue('ai');
    }

    public function handle(OpenAIProvider $openai, Usage $usage): void
    {
        $site = Site::with('customer')->findOrFail($this->siteId);
        $customer = $site->customer;

        // Personalisering från kundinställningar (med fallbackar)
        $brandVoice = $site->weekly_brand_voice ?: ($customer->weekly_brand_voice ?: 'professionell, hjälpsam');
        $audience   = $site->weekly_audience   ?: ($customer->weekly_audience   ?: 'SMB-kunder');
        $goal       = $site->weekly_goal       ?: ($customer->weekly_goal       ?: 'öka kvalificerade leads');

        $keywordsRawSite = $site->weekly_keywords ? (array) json_decode($site->weekly_keywords, true) : [];
        $keywordsRawCust = $customer->weekly_keywords ? (array) json_decode($customer->weekly_keywords, true) : [];
        $keywords = collect($keywordsRawSite ?: $keywordsRawCust)->filter()->unique()->values()->all();

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

        $period = [
            'week_start' => now()->startOfWeek()->format('Y-m-d'),
            'week_end'   => now()->endOfWeek()->format('Y-m-d'),
            'prev_week'  => [
                'start' => now()->subWeek()->startOfWeek()->format('Y-m-d'),
                'end'   => now()->subWeek()->endOfWeek()->format('Y-m-d'),
            ],
        ];

        $varsBase = [
            'brand'    => ['name' => $customer->name, 'voice' => $brandVoice],
            'audience' => $audience,
            'goal'     => $goal,
            'keywords' => $keywords,
            'context'  => $contextBlock, // <-- nyckeln för precision
            'run_tag'  => $this->runTag, // "monday" | "friday"
            'period'   => $period,
        ];

        $sections = [
            'campaigns' => $openai->generate(view('prompts.weekly.campaigns', $varsBase)->render(), ['max_tokens' => 900, 'temperature' => 0.6]),
            'topics'    => $openai->generate(view('prompts.weekly.topics', $varsBase)->render(), ['max_tokens' => 800, 'temperature' => 0.6]),
            'next_week' => $openai->generate(view('prompts.weekly.plan_next_week', $varsBase)->render(), ['max_tokens' => 1000, 'temperature' => 0.55]),
        ];

        $runDate = now()->toDateString();

        foreach (['campaigns' => 'Kampanjförslag', 'topics' => 'Aktuella ämnen', 'next_week' => 'Nästa veckas plan'] as $type => $title) {
            WeeklyPlan::create([
                'customer_id' => $customer->id,
                'site_id'     => $site->id,
                'run_date'    => $runDate,
                'run_tag'     => $this->runTag,
                'type'        => $type,
                'title'       => $title,
                'content_md'  => $sections[$type] ?? null,
                'emailed_at'  => null,
            ]);
        }

        $recipientsRaw = $site->weekly_recipients ?: $customer->weekly_recipients;
        $recipients = collect(preg_split('/\s*,\s*/', (string) $recipientsRaw, -1, PREG_SPLIT_NO_EMPTY))
            ->filter(fn($e) => filter_var($e, FILTER_VALIDATE_EMAIL))
            ->values()
            ->all();

        if (empty($recipients) && $customer->contact_email) {
            $recipients = [$customer->contact_email];
        }

        // Mottagare: weekly_recipients (komma-separerat) eller fallback till contact_email
        $cacheKey = sprintf('weekly_digest_notified:%d:%s:%s', $customer->id, $runDate, $this->runTag);
        $sentNow = Cache::add($cacheKey, 1, now()->addHours(12)); // true endast första gången

        if ($sentNow && !empty($recipients)) {
            $plansToday = WeeklyPlan::query()
                ->where('customer_id', $customer->id)
                ->whereDate('run_date', $runDate)
                ->where('run_tag', $this->runTag)
                ->get()
                ->groupBy('site_id');

            $summary = $plansToday->map(function ($items, $sid) {
                $siteName = optional($items->first()?->site)->name ?? 'Okänd sajt';
                return [
                    'site_id'   => (int) $sid,
                    'site_name' => $siteName,
                    'sections'  => $items->pluck('type')->values()->all(),
                ];
            })->values()->all();

            Mail::to($recipients)->queue(new WeeklyDigestMail(
                customer: $customer,
                runTag: $this->runTag,
                payload: [
                    'date'     => $runDate,
                    'sites'    => $summary,
                    'cta_url'  => route('dashboard'),
                    'cta_text' => 'Logga in för att läsa veckans förslag',
                ]
            ));

            WeeklyPlan::where('customer_id', $customer->id)
                ->whereDate('run_date', $runDate)
                ->where('run_tag', $this->runTag)
                ->update(['emailed_at' => now()]);
        }

        // Usage logg
        $usage->increment($customer->id, 'ai.weekly_digest');
    }
}
