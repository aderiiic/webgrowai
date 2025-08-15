<?php

namespace App\Jobs;

use App\Models\Lead;
use App\Support\LeadScoring;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class RecalculateLeadScoresJob implements ShouldQueue
{
    use Queueable;

    public function __construct(public ?int $siteId = null)
    {
        $this->onQueue('default');
    }

    public function handle(LeadScoring $scoring): void
    {
        $query = Lead::query();
        if ($this->siteId) {
            $query->where('site_id', $this->siteId);
        }

        $query->orderBy('id')->chunk(200, function ($chunk) use ($scoring) {
            foreach ($chunk as $lead) {
                $scoring->scoreLead($lead);
            }
        });
    }
}
