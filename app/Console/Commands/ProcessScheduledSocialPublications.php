<?php

namespace App\Console\Commands;

use App\Jobs\PublishAiContentJob;
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
    protected $description = 'Bearbeta schemalagda publiceringar (FB/IG/LI + WP/Shopify)';

    public function handle(): int
    {
        $now = Carbon::now();

        // Ta både 'queued' och 'scheduled' så vi inte tappar poster
        $base = ContentPublication::query()
            ->whereNotNull('scheduled_at')
            ->where('scheduled_at', '<=', $now)
            ->whereIn('status', ['queued','scheduled'])
            ->orderBy('id');

        $batch = (clone $base)->limit(200)->get();
        $counts = ['facebook'=>0,'instagram'=>0,'linkedin'=>0,'wp'=>0,'shopify'=>0,'other'=>0];

        foreach ($batch as $pub) {
            $target = $pub->target === 'wordpress' ? 'wp' : $pub->target;

            if ($target === 'facebook') {
                dispatch(new PublishToFacebookJob($pub->id))->onQueue('social')->afterCommit();
                $counts['facebook']++;
                continue;
            }
            if ($target === 'instagram') {
                dispatch(new PublishToInstagramJob($pub->id))->onQueue('social')->afterCommit();
                $counts['instagram']++;
                continue;
            }
            if ($target === 'linkedin') {
                dispatch(new PublishToLinkedInJob($pub->id))->onQueue('social')->afterCommit();
                $counts['linkedin']++;
                continue;
            }

            // Webb: wp/shopify/site → använd PublishAiContentJob
            if (in_array($target, ['wp','shopify','site'], true)) {
                $content = $pub->content; // kräver relationen
                if (!$content) {
                    $counts['other']++; continue;
                }

                $siteId = (int) ($pub->payload['site_id'] ?? $content->site_id ?? 0);
                $status = (string) ($pub->payload['status'] ?? 'publish'); // 'future' eller 'publish'

                dispatch(new PublishAiContentJob(
                    aiContentId: (int) $content->id,
                    siteId: $siteId,
                    status: $status,
                    scheduleAtIso: null, // due nu
                    publicationId: (int) $pub->id
                ))->onQueue('publish')->afterCommit();

                $counts[$target === 'site' ? 'wp' : $target]++; // lägg på wp som default
                continue;
            }

            $counts['other']++;
        }

        Log::info('[Scheduler] Processed scheduled publications', [
            'total' => array_sum($counts),
            'by_target' => $counts,
        ]);

        $this->info("Bearbetade ".array_sum($counts)." scheduled: ".json_encode($counts));
        return self::SUCCESS;
    }
}
