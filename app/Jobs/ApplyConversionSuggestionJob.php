<?php

namespace App\Jobs;

use App\Models\ConversionSuggestion;
use App\Models\WpIntegration;
use App\Services\WordPressClient;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class ApplyConversionSuggestionJob implements ShouldQueue
{
    use Queueable;

    public function __construct(public int $suggestionId)
    {
        $this->onQueue('default');
    }

    public function handle(): void
    {
        $sug = ConversionSuggestion::with('site')->findOrFail($this->suggestionId);
        $integration = WpIntegration::where('site_id', $sug->site_id)->firstOrFail();
        $wp = WordPressClient::for($integration);

        $s = $sug->suggestions ?? [];
        $title = $s['title']['suggested'] ?? null;
        $subtitle = $s['title']['subtitle'] ?? null;
        $ctaCopy = $s['cta']['suggested'] ?? null;

        $payload = [];

        if ($title) {
            $payload['title'] = $title;
        }

        // MVP: lägg CTA-kopyn i excerpt så det syns i WP (kan användas av tema/hero-block)
        if ($ctaCopy) {
            $payload['excerpt'] = $ctaCopy;
        }

        if (!empty($payload)) {
            if ($sug->wp_type === 'page') {
                $wp->updatePage($sug->wp_post_id, $payload);
            } else {
                $wp->updatePost($sug->wp_post_id, $payload);
            }

            $sug->update([
                'status' => 'applied',
                'applied_at' => now(),
            ]);
        }
    }
}
