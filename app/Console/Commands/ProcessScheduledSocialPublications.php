<?php

namespace App\Console\Commands;

use App\Jobs\PublishToFacebookJob;
use App\Jobs\PublishToInstagramJob;
use App\Jobs\PublishToLinkedInJob;
use App\Models\ContentPublication;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class ProcessScheduledSocialPublications extends Command
{
    protected $signature = 'social:process-scheduled';
    protected $description = 'Bearbeta schemalagda sociala publiceringar för FB/IG/LinkedIn';

    public function handle(): int
    {
        $now = Carbon::now();
        $targets = ['facebook', 'instagram', 'linkedin'];
        $total = 0;
        $perTarget = [];

        foreach ($targets as $target) {
            $query = ContentPublication::where('target', $target)
                ->where('status', 'queued')
                ->whereNotNull('scheduled_at')
                ->where('scheduled_at', '<=', $now)
                ->orderBy('id');

            $batch = $query->limit(100)->get(); // skydda mot massor
            $count = 0;

            foreach ($batch as $pub) {
                // Dispatcha respektive jobb
                if ($target === 'facebook') {
                    dispatch(new PublishToFacebookJob($pub->id))->onQueue('social');
                } elseif ($target === 'instagram') {
                    dispatch(new PublishToInstagramJob($pub->id))->onQueue('social');
                } else {
                    dispatch(new PublishToLinkedInJob($pub->id))->onQueue('social');
                }

                // Markera som processing (valfritt men brukar underlätta status)
                // $pub->update(['status' => 'processing']);
                $count++;
            }

            $perTarget[$target] = $count;
            $total += $count;
        }

        Log::info('[Scheduler] Processed social scheduled publications', [
            'total' => $total,
            'by_target' => $perTarget,
        ]);

        $this->info("Bearbetade {$total} schemalagda sociala publiceringar: " . json_encode($perTarget));
        return self::SUCCESS;
    }
}
