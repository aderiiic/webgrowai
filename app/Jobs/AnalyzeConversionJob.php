<?php

namespace App\Jobs;

use App\Models\Integration;
use App\Services\Sites\IntegrationManager;
use App\Support\Usage;
use App\Models\ConversionSuggestion;
use App\Models\Site;
use App\Models\WpIntegration;
use App\Services\AI\AiProviderManager;
use App\Services\Billing\QuotaGuard;
use App\Services\WordPressClient;
use Illuminate\Bus\Queueable;
use GuzzleHttp\Client as HttpClient;
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

    public function handle(AiProviderManager $manager, QuotaGuard $quota, Usage $usage, IntegrationManager $integrations): void
    {
        $site = Site::with('customer')->findOrFail($this->siteId);
        $customer = $site->customer;

        // 1) Hämta valfri integration (wordpress|shopify|custom)
        $integration = Integration::where('site_id', $site->id)->first();

        if (!$integration) {
            Log::warning('[AnalyzeConversion] ingen integration kopplad, hoppar över', [
                'customer_id' => $customer?->id,
                'site_id'     => $site->id,
            ]);
            return;
        }

        // 2) Försök hämta landningssidor
        $pages = [];
        try {
            // a) Om WordPress: använd befintligt flöde för sidor
            if ($integration->provider === 'wordpress') {
                // Använd adapterklient via IntegrationManager om tillgängligt
                $client = $integrations->forIntegration($integration);

                // Antag att adaptern har en metod getPages eller listPages. Försök båda.
                if (method_exists($client, 'getPages')) {
                    $pages = $client->getPages(['per_page' => $this->limit, 'orderby' => 'modified']);
                } elseif (method_exists($client, 'listPages')) {
                    $pages = $client->listPages($this->limit);
                }
            } else {
                // b) Icke-WP: försök via adapter först...
                try {
                    $client = $integrations->forIntegration($integration);
                    if (method_exists($client, 'getPages')) {
                        $pages = $client->getPages(['limit' => $this->limit]);
                    } elseif (method_exists($client, 'listPages')) {
                        $pages = $client->listPages($this->limit);
                    }
                } catch (\Throwable $e) {
                    // Adapter saknas eller kastar fel – vi faller tillbaka på startsidan nedan
                }
            }
        } catch (\Throwable $e) {
            Log::warning('[AnalyzeConversion] kunde inte hämta sidor via adapter', [
                'customer_id' => $customer?->id,
                'site_id'     => $site->id,
                'provider'    => $integration->provider,
                'error'       => $e->getMessage(),
            ]);
        }

        // Fallback: om inga sidor kunde hämtas via integration, analysera åtminstone startsidan
        if (empty($pages)) {
            try {
                $http = new HttpClient(['timeout' => 10]);
                $res = $http->get($site->url);
                $html = (string) $res->getBody();

                $title = '';
                if (preg_match('/<title[^>]*>(.*?)<\/title>/is', $html, $m)) {
                    $title = Str::of($m[1])->stripTags()->squish()->toString();
                }

                $pages = [[
                    'id'      => 0,
                    'link'    => $site->url,
                    'title'   => ['rendered' => $title],
                    'content' => ['rendered' => $html],
                ]];
            } catch (\Throwable $e) {
                Log::warning('[AnalyzeConversion] kunde inte hämta startsidan', [
                    'site_id' => $site->id,
                    'url'     => $site->url,
                    'error'   => $e->getMessage(),
                ]);
                return;
            }
        }

        // 3) Kör AI-analysen per sida
        $prov = $manager->choose(null, 'short');
        $guidelines = "Du är en svensk CRO-specialist. Ge konkreta förbättringar utan meta-kommentarer, inga emojis.";

        foreach ($pages as $p) {
            try {
                $quota->checkOrFail($customer, 'ai.generate');
            } catch (\Throwable $e) {
                Log::warning('[AnalyzeConversion] blocked by quota', [
                    'customer_id' => $customer->id,
                    'site_id'     => $site->id,
                    'page_id'     => Arr::get($p, 'id'),
                    'error'       => $e->getMessage(),
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
