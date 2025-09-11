<?php

namespace App\Console\Commands;

use App\Jobs\RefreshPublicationMetricsJob;
use App\Models\ContentPublication;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class RefreshRecentPublicationMetrics extends Command
{
    protected $signature = 'metrics:refresh-recent {--hours=72} {--stale=6}';
    protected $description = 'Uppdatera metrics för nyligen publicerade inlägg (senaste X timmarna)';

    public function handle(): int
    {
        $hours = (int) $this->option('hours');
        $staleHours = (int) $this->option('stale');

        $from = Carbon::now()->subHours($hours);

        $q = ContentPublication::query()
            ->where('status', 'published')
            ->where('created_at', '>=', $from);

        // Hämta i batchar
        $count = 0;
        $q->orderBy('id')
            ->chunkById(200, function ($batch) use (&$count, $staleHours) {
                foreach ($batch as $pub) {
                    $updatedAt = $pub->metrics_refreshed_at;
                    $stale = !$updatedAt || $updatedAt->lt(now()->subHours($staleHours));
                    if ($stale) {
                        dispatch(new RefreshPublicationMetricsJob($pub->id))
                            ->onQueue('metrics')
                            ->afterCommit();
                        $count++;
                    }
                }
            });

        $this->info("Köade metrics-uppdateringar för {$count} publikation(er).");
        return self::SUCCESS;
    }
}
