<?php

namespace App\Jobs;

use App\Models\ContentPublication;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Carbon;
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
        $now = Carbon::now();

        // Plocka due poster per batch
        $due = ContentPublication::query()
            ->whereNotNull('scheduled_at')
            ->where('scheduled_at', '<=', $now)
            ->whereIn('status', ['scheduled','queued'])
            ->orderBy('id')
            ->limit(200)
            ->get();

        $counts = [
            'wp' => 0, 'shopify' => 0,
            'facebook' => 0, 'instagram' => 0, 'linkedin' => 0,
            'other' => 0,
        ];

        foreach ($due as $pub) {
            $target = $pub->target;
            // Normalisera alias
            if ($target === 'wordpress') $target = 'wp';

            switch ($target) {
                case 'facebook':
                    dispatch(new PublishToFacebookJob($pub->id))->onQueue('social')->afterCommit();
                    $counts['facebook']++;
                    break;

                case 'instagram':
                    dispatch(new PublishToInstagramJob($pub->id))->onQueue('social')->afterCommit();
                    $counts['instagram']++;
                    break;

                case 'linkedin':
                    dispatch(new PublishToLinkedInJob($pub->id))->onQueue('social')->afterCommit();
                    $counts['linkedin']++;
                    break;

                case 'wp':
                case 'shopify':
                case 'site':
                    // Webbpublicering via PublishAiContentJob
                    $content = $pub->content; // AiContent-relation
                    if (!$content) { $counts['other']++; break; }

                    $siteId = (int) ($pub->payload['site_id'] ?? $content->site_id ?? 0);
                    $status = (string) ($pub->payload['status'] ?? 'publish'); // 'future' eller 'publish'
                    // När det är "due" kör vi nu; låt jobben hantera ev. datum i payload
                    dispatch(new PublishAiContentJob(
                        aiContentId: (int) $content->id,
                        siteId: $siteId,
                        status: $status,
                        scheduleAtIso: null,
                        publicationId: (int) $pub->id
                    ))->onQueue('publish')->afterCommit();
                    $counts[$target]++;
                    break;

                default:
                    $counts['other']++;
                    break;
            }
        }

        Log::info('[Scheduler] Processed due scheduled publications', [
            'total' => array_sum($counts),
            'by_target' => $counts,
        ]);
    }
}
