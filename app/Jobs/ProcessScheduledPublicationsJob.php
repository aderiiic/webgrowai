<?php

namespace App\Jobs;

use App\Models\ContentPublication;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class ProcessScheduledPublicationsJob implements ShouldQueue
{
    use Queueable;

    public function __construct()
    {
        $this->onQueue('social');
    }

    public function handle(): void
    {
        $now = now();

        // Instagram: kör alla queued vars scheduled_at <= nu
        $igs = ContentPublication::query()
            ->where('target', 'instagram')
            ->where('status', 'queued')
            ->whereNotNull('scheduled_at')
            ->where('scheduled_at', '<=', $now)
            ->limit(50)
            ->get();

        foreach ($igs as $pub) {
            dispatch(new PublishToInstagramJob($pub->id))->onQueue('social');
        }

        Log::info('[Scheduler] Processed IG scheduled publications', ['count' => $igs->count()]);
    }
}
