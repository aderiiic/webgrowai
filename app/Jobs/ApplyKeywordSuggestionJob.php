<?php

namespace App\Jobs;

use App\Models\KeywordSuggestion;
use App\Services\Sites\IntegrationManager;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class ApplyKeywordSuggestionJob implements ShouldQueue
{
    use Queueable;

    public function __construct(public int $suggestionId)
    {
        $this->onQueue('default');
    }

    public function handle(IntegrationManager $integrations): void
    {
        $sug = KeywordSuggestion::with('site')->findOrFail($this->suggestionId);
        $client = $integrations->forSite($sug->site_id);

        if (!$client->supports('update_meta')) {
            $sug->update(['status' => 'unsupported', 'applied_at' => now()]);
            return;
        }

        $payload = [];
        if (!empty($sug->suggested['title'])) $payload['title'] = $sug->suggested['title'];
        if (!empty($sug->suggested['meta']))  $payload['meta']  = $sug->suggested['meta'];

        if (!empty($payload)) {
            $client->updateDocument($sug->wp_post_id, $sug->wp_type ?? 'page', $payload);
            $sug->update(['status' => 'applied', 'applied_at' => now()]);
        }
    }
}
