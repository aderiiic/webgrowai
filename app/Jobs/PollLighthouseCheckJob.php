<?php

namespace App\Jobs;

use App\Models\SeoAudit;
use GuzzleHttp\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;

class PollLighthouseCheckJob implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public int $auditId,
        public string $checkId // kvar för bakåtkompatibilitet, används ej i PSI-läge
    ) {}

    private function buildQueryString(array $base, array $categories = []): string
    {
        $qs = http_build_query($base, '', '&', PHP_QUERY_RFC3986);
        foreach ($categories as $cat) {
            $qs .= '&category=' . rawurlencode($cat);
        }
        return $qs;
    }

    private function requestPsi(string $url, string $strategy, array $categories): array
    {
        $key = config('services.pagespeed.key');
        if (!$key) {
            throw new \RuntimeException('PAGESPEED_API_KEY saknas i .env');
        }

        $base = [
            'url' => $url,
            'key' => $key,
            'strategy' => $strategy,
        ];

        $qs = $this->buildQueryString($base, $categories);
        $full = 'https://www.googleapis.com/pagespeedonline/v5/runPagespeed?'.$qs;

        $client = new Client(['timeout' => 60]);
        $res = $client->get($full, ['timeout' => 60]);

        return json_decode((string) $res->getBody(), true);
    }

    private function score(?array $data, string $cat): ?int
    {
        if (!$data) return null;
        $val = Arr::get($data, "lighthouseResult.categories.$cat.score");
        return $val === null ? null : (int) round(((float) $val) * 100);
    }

    public function handle(): void
    {
        $audit = SeoAudit::with('site')->findOrFail($this->auditId);
        $url = $audit->site->url;

        try {
            $primary  = config('services.pagespeed.strategy', 'mobile');
            $fallback = $primary === 'mobile' ? 'desktop' : 'mobile';
            $cats = ['performance','accessibility','best-practices','seo'];

            // Ett försök för alla
            $data = $this->requestPsi($url, $primary, $cats);

            // Fyll saknade per kategori, primär sedan fallback
            foreach ($cats as $cat) {
                if (Arr::get($data, "lighthouseResult.categories.$cat.score") === null) {
                    $single = $this->requestPsi($url, $primary, [$cat]);
                    if (Arr::get($single, "lighthouseResult.categories.$cat.score") !== null) {
                        $data['lighthouseResult']['categories'][$cat] = $single['lighthouseResult']['categories'][$cat];
                        continue;
                    }
                    $singleFb = $this->requestPsi($url, $fallback, [$cat]);
                    if (Arr::get($singleFb, "lighthouseResult.categories.$cat.score") !== null) {
                        $data['lighthouseResult']['categories'][$cat] = $singleFb['lighthouseResult']['categories'][$cat];
                    }
                }
            }

            $scores = [
                'performance'    => $this->score($data, 'performance'),
                'accessibility'  => $this->score($data, 'accessibility'),
                'best-practices' => $this->score($data, 'best-practices'),
                'seo'            => $this->score($data, 'seo'),
            ];

            $audit->update([
                'lighthouse_performance'    => $scores['performance'],
                'lighthouse_accessibility'  => $scores['accessibility'],
                'lighthouse_best_practices' => $scores['best-practices'],
                'lighthouse_seo'            => $scores['seo'],
                'summary'                   => [
                    'checked_at'        => now()->toIso8601String(),
                    'strategy_primary'  => $primary,
                    'strategy_fallback' => $fallback,
                ],
            ]);

            Log::info("[SEO Audit] PSI-uppdatering klar för audit {$audit->id}");
        } catch (\Throwable $e) {
            Log::error('[SEO Audit] PSI-uppdatering misslyckades', [
                'audit_id' => $audit->id,
                'error'    => $e->getMessage()
            ]);
        }
    }
}
