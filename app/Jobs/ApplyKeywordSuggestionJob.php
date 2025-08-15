<?php

namespace App\Jobs;

use App\Models\KeywordSuggestion;
use App\Models\WpIntegration;
use App\Services\WordPressClient;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class ApplyKeywordSuggestionJob implements ShouldQueue
{
    use Queueable;

    public function __construct(public int $suggestionId)
    {
        $this->onQueue('default');
    }

    public function handle(): void
    {
        $sug = KeywordSuggestion::with('site')->findOrFail($this->suggestionId);
        $integration = WpIntegration::where('site_id', $sug->site_id)->firstOrFail();
        $wp = WordPressClient::for($integration);
        $payload = [];

        $title = $sug->suggested['title'] ?? null;
        $meta  = $sug->suggested['meta'] ?? null;

        if ($title) $payload['title'] = $title;
        if ($meta)  $payload['excerpt'] = $meta;

        if (!empty($payload)) {
            if ($sug->wp_type === 'page') {
                $wp->updatePage($sug->wp_post_id, $payload);
            } else {
                $wp->updatePost($sug->wp_post_id, $payload);
            }
            $sug->update(['status' => 'applied', 'applied_at' => now()]);
        }
    }
}
