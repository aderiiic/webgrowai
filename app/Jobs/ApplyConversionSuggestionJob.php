<?php

namespace App\Jobs;

use App\Models\ConversionSuggestion;
use App\Services\Sites\IntegrationManager;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class ApplyConversionSuggestionJob implements ShouldQueue
{
    use Queueable;

    public function __construct(public int $suggestionId)
    {
        $this->onQueue('default');
    }

    public function handle(IntegrationManager $integrations): void
    {
        $sug = ConversionSuggestion::with('site')->findOrFail($this->suggestionId);
        $client = $integrations->forSite($sug->site_id);

        if (!$client->supports('update_meta')) {
            $sug->update(['status' => 'unsupported', 'applied_at' => now()]);
            return;
        }

        $s = $sug->suggestions ?? [];
        $payload = [];
        if (!empty($s['title']['suggested'])) $payload['title'] = $s['title']['suggested'];
        if (!empty($s['cta']['suggested']))   $payload['meta']  = $s['cta']['suggested'];

        if (!empty($payload)) {
            $client->updateDocument($sug->wp_post_id, $sug->wp_type ?? 'page', $payload);
            $sug->update(['status' => 'applied', 'applied_at' => now()]);
        }
    }
}
