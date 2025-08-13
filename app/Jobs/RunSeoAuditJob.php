<?php

namespace App\Jobs;

use App\Models\{SeoAudit, Site, WpIntegration};
use App\Services\WordPressClient;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class RunSeoAuditJob implements ShouldQueue
{
    use Queueable;

    public function __construct(public int $siteId) {}

    private function httpClient(array $headers = []): Client
    {
        $token = config('services.lighthouse.token');
        $hdrs = array_merge([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ], $headers);

        if ($token) {
            $hdrs['Authorization'] = $token;        }

        return new Client(['timeout' => 60, 'headers' => $hdrs]);
    }

    private function startLighthouseCheck(string $base, string $url): string
    {
        $client = $this->httpClient();
        $endpoint = rtrim($base, '/').'/lighthouse/checks';

        // Regions: kräv minst en
        $regions = config('services.lighthouse.regions');
        if (empty($regions)) {
            $single = config('services.lighthouse.region');
            if (!empty($single)) {
                $regions = [$single];
            } else {
                // Fallback om inget är satt
                $regions = ['us-east4'];
            }
        }

        $payload = [
            'url' => $url,
            'regions' => $regions, // VIKTIGT: API kräver array
        ];

        // Valfritt: device/strategy (om API stöder)
        if ($device = config('services.lighthouse.device')) {
            $payload['device'] = $device;                  // vissa API:n
            $payload['formFactor'] = $device;              // andra
            $payload['strategy'] = $device === 'desktop' ? 'desktop' : 'mobile'; // PageSpeed-stil
        }

        try {
            $res = $client->post($endpoint, ['json' => $payload, 'timeout' => 60]);
            $json = json_decode((string) $res->getBody(), true);
            $id = Arr::get($json, 'id') ?? Arr::get($json, 'check.id');
            if (!$id) {
                throw new \RuntimeException('Inget checkId i svaret: '.json_encode($json, JSON_UNESCAPED_UNICODE));
            }
            Log::info('[SEO Audit] Lighthouse check skapad', ['checkId' => $id, 'regions' => $regions]);
            return (string) $id;
        } catch (RequestException $e) {
            $status = $e->getResponse()?->getStatusCode();
            $body = (string) ($e->getResponse()?->getBody() ?? '');
            throw new \RuntimeException("Kunde inte skapa Lighthouse-check (status {$status}): {$body}");
        }
    }

    private function pollLighthouseCheck(string $base, string $checkId, int $timeoutSeconds = 90, int $intervalMs = 2000): array
    {
        $client = $this->httpClient();
        $endpoint = rtrim($base, '/').'/lighthouse/checks/'.urlencode($checkId);

        $start = microtime(true);
        do {
            try {
                $res = $client->get($endpoint, ['timeout' => 30]);
                $data = json_decode((string) $res->getBody(), true);

                $state = strtolower((string) (Arr::get($data, 'state') ?? Arr::get($data, 'check.state', '')));
                if (in_array($state, ['succeeded','failed','error'], true)) {
                    Log::info('[SEO Audit] Lighthouse check klar', ['checkId' => $checkId, 'state' => $state]);
                    return $data;
                }
            } catch (\Throwable $e) {
                Log::warning('[SEO Audit] Poll-fel', ['checkId' => $checkId, 'msg' => $e->getMessage()]);
            }
            usleep($intervalMs * 1000);
        } while ((microtime(true) - $start) < $timeoutSeconds);

        throw new \RuntimeException('Timeout vid hämtning av Lighthouse-resultat.');
    }

    public function handle(): void
    {
        $site = Site::findOrFail($this->siteId);
        Log::info('[SEO Audit] Start', ['site_id' => $site->id, 'url' => $site->url]);

        $integration = WpIntegration::where('site_id', $site->id)->first();

        $audit = SeoAudit::create([
            'site_id' => $site->id,
            'title_issues' => 0,
            'meta_issues' => 0,
        ]);

        $titleIssues = 0;
        $metaIssues = 0;

        // WP-analys (oförändrad)
        if ($integration) {
            try {
                $wp = WordPressClient::for($integration);
                $posts = $wp->getPosts(['per_page' => 20, 'status' => 'any']);
                foreach ($posts as $p) {
                    $postId = $p['id'] ?? null;
                    $postLink = $p['link'] ?? $site->url;

                    $title = trim(strip_tags($p['title']['rendered'] ?? ''));
                    if ($title === '' || Str::length($title) > 60) {
                        $titleIssues++;
                        $audit->items()->create([
                            'type' => 'title',
                            'page_url' => $postLink,
                            'message' => $title === '' ? 'Saknar titel' : 'Titel är för lång (>60 tecken)',
                            'severity' => 'medium',
                            'data' => ['length' => Str::length($title), 'post_id' => $postId],
                        ]);
                    }

                    $meta = trim(strip_tags($p['excerpt']['rendered'] ?? ''));
                    if ($meta === '' || Str::length($meta) > 160) {
                        $metaIssues++;
                        $audit->items()->create([
                            'type' => 'meta',
                            'page_url' => $postLink,
                            'message' => $meta === '' ? 'Saknar meta description' : 'Meta description är för lång (>160 tecken)',
                            'severity' => 'low',
                            'data' => ['length' => Str::length($meta), 'post_id' => $postId],
                        ]);
                    }
                }
            } catch (\Throwable $e) {
                Log::error('[SEO Audit] WP-analys fel', ['site_id' => $site->id, 'error' => $e->getMessage()]);
                $audit->items()->create([
                    'type' => 'title',
                    'page_url' => $site->url,
                    'message' => 'WP-analys misslyckades',
                    'severity' => 'high',
                ]);
            }
        }

        // Lighthouse: create + poll
        $lhPerf = 0; $lhAcc = 0; $lhBP = 0; $lhSEO = 0;
        try {
            $base = rtrim(config('services.lighthouse.url') ?? '', '/');
            if ($base === '') {
                throw new \RuntimeException('Lighthouse URL saknas i konfigurationen');
            }

            $checkId = $this->startLighthouseCheck($base, $site->url);
            $result = $this->pollLighthouseCheck($base, $checkId, 120, 2000);

            $run = Arr::first(Arr::get($result, 'runs', []), fn() => true) ?? [];

            $lhPerf = (int) ($run['performance'] ?? 0);
            $lhAcc  = (int) ($run['accessibility'] ?? 0);
            $lhBP   = (int) ($run['bestPractices'] ?? 0);
            $lhSEO  = (int) ($run['seo'] ?? 0);
        } catch (\Throwable $e) {
            Log::error('[SEO Audit] Lighthouse fel', ['site_id' => $site->id, 'error' => $e->getMessage()]);
            $audit->items()->create([
                'type' => 'lighthouse',
                'page_url' => $site->url,
                'message' => 'Lighthouse misslyckades',
                'severity' => 'high',
                'data' => ['hint' => 'Sätt LIGHTHOUSE_API_REGIONS (kommaseparerat) i .env, t.ex. us-east4', 'details' => $e->getMessage()],
            ]);
        }

        $audit->update([
            'lighthouse_performance' => $lhPerf,
            'lighthouse_accessibility' => $lhAcc,
            'lighthouse_best_practices' => $lhBP,
            'lighthouse_seo' => $lhSEO,
            'title_issues' => $titleIssues,
            'meta_issues' => $metaIssues,
            'summary' => [
                'checked_at' => now()->toIso8601String(),
                'pages_sampled' => isset($posts) ? count($posts) : 0,
            ],
        ]);

        Log::info('[SEO Audit] Klar', [
            'site_id' => $site->id,
            'perf' => $lhPerf, 'acc' => $lhAcc, 'bp' => $lhBP, 'seo' => $lhSEO,
            'title_issues' => $titleIssues, 'meta_issues' => $metaIssues,
        ]);
    }
}
