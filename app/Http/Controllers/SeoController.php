<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\URL;

class SeoController extends Controller
{
    public function sitemap(Request $request)
    {
        $key = 'sitemap.xml.v1';
        $xml = Cache::remember($key, now()->addHour(), function () {
            $base = rtrim(config('app.url') ?: URL::to('/'), '/');

            // 1) Statiska URL:er
            $static = [
                [
                    'loc'        => $base . '/',
                    'changefreq' => 'weekly',
                    'priority'   => '0.9',
                    'lastmod'    => Carbon::now()->toAtomString(),
                ],
                [
                    'loc'        => $base . '/news',
                    'changefreq' => 'daily',
                    'priority'   => '0.7',
                    'lastmod'    => Carbon::now()->toAtomString(),
                ],
                [
                    'loc'        => $base . '/pricing',
                    'changefreq' => 'monthly',
                    'priority'   => '0.6',
                    'lastmod'    => Carbon::now()->toAtomString(),
                ],
                [
                    'loc'        => $base . '/integritet',
                    'changefreq' => 'yearly',
                    'priority'   => '0.3',
                    'lastmod'    => Carbon::now()->toAtomString(),
                ],
                [
                    'loc'        => $base . '/villkor',
                    'changefreq' => 'yearly',
                    'priority'   => '0.3',
                    'lastmod'    => Carbon::now()->toAtomString(),
                ],
                [
                    'loc'        => $base . '/gratis-hemsida',
                    'changefreq' => 'monthly',
                    'priority'   => '0.5',
                    'lastmod'    => Carbon::now()->toAtomString(),
                ],
            ];

            // 2) Dynamiska poster (nyheter)
            $posts = Post::query()
                ->whereNotNull('published_at')
                ->latest('published_at')
                ->limit(2000) // hållbar gräns
                ->get(['slug', 'updated_at', 'published_at']);

            $items = $static;

            foreach ($posts as $post) {
                $lastmod = ($post->updated_at ?? $post->published_at ?? now())->toAtomString();
                $items[] = [
                    'loc'        => $base . '/news/' . $post->slug,
                    'changefreq' => 'weekly',
                    'priority'   => '0.8',
                    'lastmod'    => $lastmod,
                ];
            }

            // Bygg XML
            $dom = new \DOMDocument('1.0', 'UTF-8');
            $dom->formatOutput = true;

            $urlset = $dom->createElement('urlset');
            $urlset->setAttribute('xmlns', 'http://www.sitemaps.org/schemas/sitemap/0.9');
            $dom->appendChild($urlset);

            foreach ($items as $row) {
                $url = $dom->createElement('url');

                $loc = $dom->createElement('loc', htmlspecialchars($row['loc'], ENT_XML1 | ENT_COMPAT, 'UTF-8'));
                $url->appendChild($loc);

                if (!empty($row['lastmod'])) {
                    $url->appendChild($dom->createElement('lastmod', $row['lastmod']));
                }

                if (!empty($row['changefreq'])) {
                    $url->appendChild($dom->createElement('changefreq', $row['changefreq']));
                }

                if (!empty($row['priority'])) {
                    $url->appendChild($dom->createElement('priority', $row['priority']));
                }

                $urlset->appendChild($url);
            }

            return $dom->saveXML();
        });

        return response($xml, 200, [
            'Content-Type' => 'application/xml; charset=UTF-8',
        ]);
    }

    public function robots(Request $request)
    {
        $isProd = app()->environment('production');
        $base   = rtrim(config('app.url') ?: URL::to('/'), '/');

        if (!$isProd) {
            // Blockera allt i icke‑prod
            $content = <<<TXT
User-agent: *
Disallow: /
TXT;
            return response($content, 200, ['Content-Type' => 'text/plain; charset=UTF-8']);
        }

        // Production: tillåt indexering av publika sidor, blockera interna/admindelar
        $lines = [
            'User-agent: *',
            'Disallow: /admin',
            'Disallow: /login',
            'Disallow: /register',
            'Disallow: /dashboard',
            'Disallow: /leads',
            'Disallow: /analytics',
            'Disallow: /settings',
            'Disallow: /sites',
            'Disallow: /ai',
            'Disallow: /cro',
            'Disallow: /seo',
            'Allow: /news$',
            'Allow: /news/',
            'Allow: /assets/',
            'Sitemap: ' . $base . '/sitemap.xml',
        ];

        return response(implode("\n", $lines) . "\n", 200, [
            'Content-Type' => 'text/plain; charset=UTF-8',
        ]);
    }
}
