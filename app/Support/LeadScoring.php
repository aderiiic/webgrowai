<?php

namespace App\Support;

use App\Models\Lead;
use App\Models\LeadEvent;
use App\Models\LeadScore;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class LeadScoring
{
    // Maximalt råpoäng innan normalisering
    private int $cap = 150;

    public function scoreLead(Lead $lead): void
    {
        $since30d = now()->subDays(30);
        $events = LeadEvent::where('lead_id', $lead->id)
            ->where('occurred_at', '>=', $since30d)
            ->orderBy('occurred_at', 'desc')
            ->get(['type','url','meta','occurred_at']);

        $raw = 0;
        $break = [
            'pageview' => 0,
            'time' => 0,
            'sessions' => 0,
            'cta' => 0,
            'form' => 0,
            'recency' => 0,
        ];

        // Baspoäng per event
        $pageviews = 0;
        foreach ($events as $ev) {
            if ($ev->type === 'pageview') {
                if ($pageviews < 5) { $raw += 1; $break['pageview'] += 1; }
                $pageviews++;
            }
            if ($ev->type === 'heartbeat') {
                $sec = (int)($ev->meta['seconds'] ?? 0);
                // 1p per 15s, max 12 (≈ 3 min) per sida/ackumulerat
                $add = min(12, (int)floor($sec / 15));
                $raw += $add; $break['time'] += $add;
            }
            if ($ev->type === 'cta') {
                $raw += 30; $break['cta'] += 30;
            }
            if ($ev->type === 'form_submit') {
                $raw += 40; $break['form'] += 40;
            }
        }

        // Sessions (utifrån lead)
        $sess = (int)$lead->sessions;
        if ($sess >= 2) {
            $raw += 10; $break['sessions'] += 10;
            if ($sess > 2) {
                $add = min(20, ($sess - 2) * 5);
                $raw += $add; $break['sessions'] += $add;
            }
        }

        // Recency boost
        $last = $lead->last_seen ?: $lead->updated_at ?: now();
        $diffH = $last->diffInHours(now());
        if ($diffH <= 24) { $raw += 10; $break['recency'] += 10; }
        elseif ($diffH <= 24*7) { $raw += 5; $break['recency'] += 5; }

        // Decay efter inaktivitet
        if ($diffH > 24*30) { $raw = (int)floor($raw * 0.5); }
        elseif ($diffH > 24*14) { $raw = (int)floor($raw * 0.8); }

        // Normalisera 1–100
        $cap = max(1, $this->cap);
        $norm = (int)round(min(100, ($raw / $cap) * 100));

        LeadScore::updateOrCreate(
            ['site_id' => $lead->site_id, 'lead_id' => $lead->id],
            [
                'score_raw' => $raw,
                'score_norm' => $norm,
                'breakdown' => $break,
                'last_calculated_at' => now(),
            ]
        );
    }
}
