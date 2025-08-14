<?php

namespace App\Jobs;

use App\Mail\WeeklyDigestMail;
use App\Models\Customer;
use App\Models\WeeklyPlan;
use App\Services\AI\OpenAIProvider;
use App\Support\Usage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class GenerateWeeklyDigestJob implements ShouldQueue
{
    use Queueable;

    public function __construct(public int $customerId, public string $runTag = 'monday') // monday|friday
    {
        $this->onQueue('ai');
    }

    public function handle(OpenAIProvider $openai, Usage $usage): void
    {
        $customer = Customer::findOrFail($this->customerId);

        // Personalisering från kundinställningar (med fallbackar)
        $brandVoice = $customer->weekly_brand_voice ?: 'professionell, hjälpsam';
        $audience   = $customer->weekly_audience ?: 'SMB-kunder';
        $goal       = $customer->weekly_goal ?: 'öka kvalificerade leads';
        $keywords   = $customer->weekly_keywords ? (array) json_decode($customer->weekly_keywords, true) : [];

        $varsBase = [
            'brand'    => ['name' => $customer->name, 'voice' => $brandVoice],
            'audience' => $audience,
            'goal'     => $goal,
            'keywords' => $keywords,
        ];

        $sections = [];

        // 1) Kampanjförslag
        $sections['campaigns'] = $openai->generate(
            view('prompts.weekly.campaigns', $varsBase)->render(),
            ['max_tokens' => 900, 'temperature' => 0.7]
        );

        // 2) Aktuella ämnen
        $sections['topics'] = $openai->generate(
            view('prompts.weekly.topics', $varsBase)->render(),
            ['max_tokens' => 800, 'temperature' => 0.7]
        );

        // 3) Nästa veckas plan
        $sections['next_week'] = $openai->generate(
            view('prompts.weekly.plan_next_week', $varsBase)->render(),
            ['max_tokens' => 1000, 'temperature' => 0.6]
        );

        $runDate = now()->toDateString();

        foreach (['campaigns' => 'Kampanjförslag', 'topics' => 'Aktuella ämnen', 'next_week' => 'Nästa veckas plan'] as $type => $title) {
            WeeklyPlan::create([
                'customer_id' => $customer->id,
                'run_date'    => $runDate,
                'run_tag'     => $this->runTag,
                'type'        => $type,
                'title'       => $title,
                'content_md'  => $sections[$type] ?? null,
                'emailed_at'  => null,
            ]);
        }

        // Mottagare: weekly_recipients (komma-separerat) eller fallback till contact_email
        $recipients = collect(preg_split('/\s*,\s*/', (string) $customer->weekly_recipients, -1, PREG_SPLIT_NO_EMPTY))
            ->filter(fn($e) => filter_var($e, FILTER_VALIDATE_EMAIL))
            ->values()
            ->all();

        if (empty($recipients) && $customer->contact_email) {
            $recipients = [$customer->contact_email];
        }

        if (!empty($recipients)) {
            // Skicka köat
            Mail::to($recipients)->queue(new WeeklyDigestMail($customer, $this->runTag, $sections));

            // Markera emailed_at
            WeeklyPlan::where('customer_id', $customer->id)
                ->whereDate('run_date', $runDate)
                ->where('run_tag', $this->runTag)
                ->update(['emailed_at' => now()]);
        }

        // Usage logg
        $usage->increment($customer->id, 'ai.weekly_digest');
    }
}
