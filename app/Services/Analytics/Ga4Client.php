<?php

namespace App\Services\Analytics;

use App\Models\Ga4Integration;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use League\OAuth2\Client\Provider\Google as GoogleProvider;

class Ga4Client
{
    /**
     * League Google OAuth2 provider (används för token-refresh).
     */
    private function provider(): GoogleProvider
    {
        return new GoogleProvider([
            'clientId'     => (string) config('services.google.client_id'),
            'clientSecret' => (string) config('services.google.client_secret'),
            'redirectUri'  => route('analytics.ga4.callback'),
        ]);
    }

    /**
     * Säkerställ ett giltigt access token för GA4-anrop.
     * Förlänger med refresh_token om access_token gått ut.
     */
    private function ensureAccessToken(Ga4Integration $ga): ?string
    {
        $access = $ga->access_token ? decrypt($ga->access_token) : null;

        if (!empty($access) && $ga->expires_at && now()->lt($ga->expires_at)) {
            return $access;
        }

        if (empty($ga->refresh_token)) {
            return $access; // kan vara null
        }

        try {
            $provider  = $this->provider();
            $newToken  = $provider->getAccessToken('refresh_token', [
                'refresh_token' => decrypt((string) $ga->refresh_token),
            ]);

            $token   = (string) $newToken->getToken();
            $expires = (int) ($newToken->getExpires() ?: 3600);

            $ga->update([
                'access_token' => $token ? encrypt($token) : $ga->access_token,
                'expires_at'   => now()->addSeconds($expires),
            ]);

            return $token ?: $access;
        } catch (\Throwable $e) {
            Log::warning('[GA4] Token refresh misslyckades', [
                'site_id' => $ga->site_id,
                'error'   => $e->getMessage(),
            ]);
            return $access; // låt ev. gammalt fungera om det finns
        }
    }

    /**
     * Är GA4 kopplat för sajt?
     */
    public function isConnected(int $siteId): bool
    {
        return Ga4Integration::where('site_id', $siteId)
            ->where('status', 'connected')
            ->exists();
    }

    /**
     * Hämta översiktsvärden (7d) från GA4:
     * - activeUsers (besökare)
     * - sessions
     * - trend (mot föregående 7d)
     */
    public function fetchOverview(int $siteId, string $sinceDate): array
    {
        $ga = Ga4Integration::where('site_id', $siteId)->first();
        if (!$ga || !$ga->property_id) {
            return ['connected' => false];
        }

        $token = $this->ensureAccessToken($ga);
        if (!$token) {
            return ['connected' => false];
        }

        $propertyId = $ga->property_id; // ex: "properties/123456789" eller bara id
        $propPath   = $this->normalizePropertyPath($propertyId);

        // Nuvarande period
        $current = $this->runReportSimple(
            $token,
            $propPath,
            [
                'dateRanges' => [[
                    'startDate' => $sinceDate,
                    'endDate'   => 'today',
                ]],
                'metrics'    => [
                    ['name' => 'activeUsers'],
                    ['name' => 'sessions'],
                ],
            ]
        );

        // Föregående period (samma längd)
        $since = CarbonImmutable::parse($sinceDate);
        $prevStart = $since->subDays( $since->diffInDays(CarbonImmutable::now()->startOfDay()) ?: 7 );
        // Förenklat: 7 dagar innan "sinceDate"
        $prev = $this->runReportSimple(
            $token,
            $propPath,
            [
                'dateRanges' => [[
                    'startDate' => $since->subDays(7)->format('Y-m-d'),
                    'endDate'   => $since->subDay()->format('Y-m-d'),
                ]],
                'metrics'    => [
                    ['name' => 'activeUsers'],
                    ['name' => 'sessions'],
                ],
            ]
        );

        $visitorsNow  = (int) ($current['metrics']['activeUsers'] ?? 0);
        $sessionsNow  = (int) ($current['metrics']['sessions'] ?? 0);
        $visitorsPrev = (int) ($prev['metrics']['activeUsers'] ?? 0);

        $trend = $this->pct($visitorsNow, $visitorsPrev);

        return [
            'connected'   => true,
            'visitors_7d' => $visitorsNow,
            'sessions_7d' => $sessionsNow,
            'trend_pct'   => $trend,
        ];
    }

    /**
     * Hämta page-level data för en lista URL:er (7d):
     * - screenPageViews (pageviews)
     * - sessions
     * Filtrerar på hostname om konfigurerad.
     */
    public function fetchByUrls(int $siteId, array $urls, string $sinceDate): array
    {
        $ga = Ga4Integration::where('site_id', $siteId)->first();
        if (!$ga || !$ga->property_id || empty($urls)) {
            return [];
        }

        $token = $this->ensureAccessToken($ga);
        if (!$token) {
            return [];
        }

        $propertyId = $ga->property_id;
        $propPath   = $this->normalizePropertyPath($propertyId);

        // Extrahera paths och matcha tillbaka
        $targetHost = $ga->hostname ? mb_strtolower($ga->hostname) : null;
        $pathsByUrl = [];
        foreach ($urls as $u) {
            if (!is_string($u)) continue;
            $host = parse_url($u, PHP_URL_HOST);
            $path = parse_url($u, PHP_URL_PATH) ?: '/';
            if ($targetHost && $host && mb_strtolower($host) !== $targetHost) {
                // Hoppa över annan domän om vi låst till hostname
                continue;
            }
            $pathsByUrl[$u] = $path;
        }
        if (empty($pathsByUrl)) {
            return [];
        }

        // Bygg OR-filter för pagePath
        $orExpressions = [];
        foreach (array_values($pathsByUrl) as $p) {
            $orExpressions[] = [
                'filter' => [
                    'fieldName'    => 'pagePath',
                    'stringFilter' => [
                        'matchType' => 'EXACT',
                        'value'     => $p,
                    ],
                ],
            ];
        }

        $dimensionFilter = ['orGroup' => ['expressions' => $orExpressions]];

        // Om hostname är specificerad – lägg AND med hostName
        if ($targetHost) {
            $dimensionFilter = [
                'andGroup' => [
                    'expressions' => [
                        $dimensionFilter,
                        [
                            'filter' => [
                                'fieldName'    => 'hostName',
                                'stringFilter' => [
                                    'matchType' => 'EXACT',
                                    'value'     => $targetHost,
                                ],
                            ],
                        ],
                    ],
                ],
            ];
        }

        $body = [
            'dateRanges' => [[
                'startDate' => $sinceDate,
                'endDate'   => 'today',
            ]],
            'dimensions' => [
                ['name' => 'pagePath'],
            ],
            'metrics'    => [
                ['name' => 'screenPageViews'],
                ['name' => 'sessions'],
            ],
            'dimensionFilter' => $dimensionFilter,
            'limit' => 100000, // säkra upp vid många inlägg
        ];

        $resp = $this->runReport($token, $propPath, $body);
        if (!$resp['ok']) {
            return [];
        }

        // Init output 0-värden
        $out = [];
        foreach ($pathsByUrl as $url => $p) {
            $out[$url] = ['pageviews_7d' => 0, 'sessions_7d' => 0];
        }

        foreach ((array) ($resp['data']['rows'] ?? []) as $row) {
            $path = $row['dimensionValues'][0]['value'] ?? '';
            $views = (int) ($row['metricValues'][0]['value'] ?? 0);
            $sess  = (int) ($row['metricValues'][1]['value'] ?? 0);

            // matcha tillbaka
            foreach ($pathsByUrl as $url => $p) {
                if ($p === $path) {
                    $out[$url] = ['pageviews_7d' => $views, 'sessions_7d' => $sess];
                }
            }
        }

        return $out;
    }

    // ==========================
    // Interna hjälpare
    // ==========================

    private function pct(int $latest, int $prev): int
    {
        if ($prev <= 0) {
            return $latest > 0 ? 100 : 0;
        }
        return (int) round((($latest - $prev) / max(1, $prev)) * 100);
    }

    private function normalizePropertyPath(string $propertyId): string
    {
        // Acceptera både "properties/123456" och "123456"
        return str_starts_with($propertyId, 'properties/')
            ? $propertyId
            : ('properties/' . $propertyId);
    }

    /**
     * Kör ett GA4 runReport-anrop (råt).
     */
    private function runReport(string $accessToken, string $propertyPath, array $body): array
    {
        try {
            $resp = Http::withToken($accessToken)
                ->post("https://analyticsdata.googleapis.com/v1beta/{$propertyPath}:runReport", $body);

            if (!$resp->ok()) {
                $err = $resp->json('error.message') ?: $resp->body();
                Log::warning('[GA4] runReport fel', ['property' => $propertyPath, 'error' => $err]);
                return ['ok' => false, 'data' => null];
            }

            return ['ok' => true, 'data' => $resp->json()];
        } catch (\Throwable $e) {
            Log::error('[GA4] runReport exception', [
                'property' => $propertyPath,
                'error'    => $e->getMessage(),
            ]);
            return ['ok' => false, 'data' => null];
        }
    }

    /**
     * Enklare runReport som summerar första raden till en assoc med metrics.
     */
    private function runReportSimple(string $accessToken, string $propertyPath, array $body): array
    {
        $r = $this->runReport($accessToken, $propertyPath, $body);
        if (!$r['ok']) {
            return ['metrics' => []];
        }

        $rows = (array) ($r['data']['rows'] ?? []);
        $first = $rows[0]['metricValues'] ?? null;

        // Mappar metriska namn i samma ordning som skickades
        $metrics = [];
        if (isset($body['metrics']) && is_array($body['metrics']) && is_array($first)) {
            foreach ($body['metrics'] as $idx => $m) {
                $name = $m['name'] ?? ("m{$idx}");
                $metrics[$name] = (int) ($first[$idx]['value'] ?? 0);
            }
        }

        return ['metrics' => $metrics];
    }
}
