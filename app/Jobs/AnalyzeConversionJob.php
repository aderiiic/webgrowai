<?php

namespace App\Jobs;

use App\Support\Usage;
use App\Models\ConversionSuggestion;
use App\Models\Site;
use App\Models\WpIntegration;
use App\Services\AI\AiProviderManager;
use App\Services\Billing\QuotaGuard;
use App\Services\WordPressClient;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class AnalyzeConversionJob implements ShouldQueue
{
    use Queueable;

    public function __construct(public int $siteId, public int $limit = 6)
    {
        $this->onQueue('default');
    }

    public function handle(AiProviderManager $manager, QuotaGuard $quota, Usage $usage): void
    {
        $site = Site::with('customer')->findOrFail($this->siteId);
        $customer = $site->customer;

        try {
            $integration = WpIntegration::where('site_id', $site->id)->firstOrFail();
        } catch (ModelNotFoundException $e) {
            Log::warning('[AnalyzeConversion] ingen WP-integration kopplad, hoppar över', [
                'customer_id' => $customer?->id,
                'site_id'     => $site->id,
            ]);
            return;
        }

        $wp = WordPressClient::for($integration);

        // 1) Hämta startsidan + landningssidor (enkelt: pages, publicerade, senast uppdaterade)
        try {
            $pages = $wp->getPages(['per_page' => $this->limit, 'orderby' => 'modified']);
        } catch (\Throwable $e) {
            Log::warning('[AnalyzeConversion] kunde inte hämta sidor från WP', [
                'customer_id' => $customer?->id,
                'site_id'     => $site->id,
                'error'       => $e->getMessage(),
            ]);
            return;
        }

        if (empty($pages)) {
            Log::info('[AnalyzeConversion] inga sidor hittades att analysera', [
                'site_id' => $site->id,
            ]);
            return;
        }

        $prov = $manager->choose(null, 'short');
        $guidelines = "Du är en svensk CRO-specialist. Ge konkreta förbättringar utan meta-kommentarer, inga emojis.";

        foreach ($pages as $p) {
            try {
                $quota->checkOrFail($customer, 'ai.generate');
            } catch (\Throwable $e) {
                Log::warning('[AnalyzeConversion] blocked by quota', [
                    'customer_id' => $customer->id,
                    'site_id' => $site->id,
                    'page_id' => $p['id'] ?? null,
                    'error' => $e->getMessage(),
                ]);
                break;
            }

            $pid = (int)($p['id'] ?? 0);
            $url = (string)($p['link'] ?? $site->url);
            $title = trim(strip_tags(Arr::get($p, 'title.rendered', '')));
            $html = (string)Arr::get($p, 'content.rendered', '');

            $text = Str::of($html)->replace(['<script','</script>'], ' ')->stripTags()->squish()->limit(4000);

            $prompt = $guidelines."\n\n".
                "Analys av sida (titel + innehåll, utdrag nedan). Du ska föreslå:\n".
                "- Rubrik: förbättrad H1 (max 60 tecken) och ev. underrubrik\n".
                "- CTA: primär knapptext (max 25 tecken) och placering (above fold/sektion)\n".
                "- Formulär: placering och antal fält (maximisera konvertering)\n".
                "- Kort motivering (punktlista)\n\n".
                "Titel: \"{$title}\"\nInnehåll (trimmat):\n{$text}\n\n".
                "Returnera ENDAST JSON med strukturen:\n".
                "{\n".
                "  \"insights\": [\"...\",\"...\"],\n".
                "  \"title\": {\"current\": \"...\", \"suggested\": \"...\", \"subtitle\": \"...\"},\n".
                "  \"cta\": {\"current\": \"...\", \"suggested\": \"...\", \"placement\": \"above_fold|section_2|footer\"},\n".
                "  \"form\": {\"current\": \"...\", \"suggested\": {\"placement\": \"above_fold|sidebar|section_2\", \"fields\": [\"name\",\"email\",\"phone\"]}}\n".
                "}\n";

            $json = $prov->generate($prompt, ['max_tokens' => 700, 'temperature' => 0.5]);
            // Rensa ev. icke-JSON och försök parse:a
            $json = trim(Str::of($json)->after('{')->beforeLast('}'));
            $json = '{'.$json.'}';

            $data = json_decode($json, true);
            if (!is_array($data)) continue;

            ConversionSuggestion::updateOrCreate(
                ['site_id' => $site->id, 'wp_post_id' => $pid, 'wp_type' => 'page'],
                [
                    'url' => $url,
                    'insights' => Arr::get($data, 'insights', []),
                    'suggestions' => [
                        'title' => Arr::get($data, 'title', []),
                        'cta' => Arr::get($data, 'cta', []),
                        'form' => Arr::get($data, 'form', []),
                    ],
                    'status' => 'new',
                ]
            );

            $usage->increment($customer->id, 'ai.generate', now()->format('Y-m'), 1);
        }
    }
}
