<?php
// app/Services/Sites/Providers/CustomAdapter.php

namespace App\Services\Sites\Providers;

use App\Models\Integration;
use App\Services\Sites\SiteIntegrationClient;
use GuzzleHttp\Client;
use Illuminate\Support\Str;

class CustomAdapter implements SiteIntegrationClient
{
    public function __construct(private Integration $integration) {}

    public function provider(): string { return 'custom'; }

    public function supports(string $capability): bool
    {
        // MVP: endast läsning via crawling/sitemap
        return in_array($capability, ['list','fetch'], true);
    }

    public function listDocuments(array $opts = []): array
    {
        $creds = $this->integration->credentials ?? [];
        $mode = $creds['mode'] ?? 'crawler'; // 'crawler' | 'api'
        $limit = (int)($opts['limit'] ?? 10);

        if ($mode === 'api') {
            // Förväntar endpoints: list_url (returnerar [{url,title}] )
            $listUrl = (string)($creds['endpoints']['list_url'] ?? '');
            $apiKey  = (string)($creds['api_key'] ?? '');
            if ($listUrl === '') return [];

            $client = new Client(['timeout' => 20]);
            $res = $client->get($listUrl, ['headers' => ['Authorization' => "Bearer {$apiKey}"]]);
            $arr = json_decode((string)$res->getBody(), true);
            $out = [];
            foreach (array_slice($arr ?? [], 0, $limit) as $i => $row) {
                $out[] = [
                    'id' => $i + 1,
                    'type' => 'page',
                    'url' => (string)($row['url'] ?? ''),
                    'title' => (string)($row['title'] ?? ''),
                    'excerpt' => '',
                ];
            }
            return $out;
        }

        // crawler‑läge: läs sitemap
        $sitemap = (string)($creds['sitemap_url'] ?? '');
        if ($sitemap === '') return [];
        $client = new Client(['timeout' => 20]);
        $res = $client->get($sitemap);
        $xml = simplexml_load_string((string)$res->getBody());
        if (!$xml) return [];

        $urls = [];
        foreach ($xml->url as $u) {
            $loc = (string)$u->loc;
            if ($loc) $urls[] = $loc;
            if (count($urls) >= $limit) break;
        }

        $out = [];
        foreach ($urls as $idx => $u) {
            $out[] = ['id' => $idx + 1, 'type' => 'page', 'url' => $u, 'title' => '', 'excerpt' => ''];
        }
        return $out;
    }

    public function getDocument(int|string $id, string $type): array
    {
        $creds = $this->integration->credentials ?? [];
        $mode = $creds['mode'] ?? 'crawler';

        if ($mode === 'api') {
            $getUrl = (string)($creds['endpoints']['get_url'] ?? ''); // t.ex. https://api.example.com/doc?Id={id}
            $apiKey = (string)($creds['api_key'] ?? '');
            if ($getUrl === '') return ['id' => (int)$id, 'type' => 'page', 'url' => '', 'title' => '', 'excerpt' => '', 'html' => ''];
            $url = str_replace('{id}', (string)$id, $getUrl);
            $client = new \GuzzleHttp\Client(['timeout' => 20]);
            $res = $client->get($url, ['headers' => ['Authorization' => "Bearer {$apiKey}"]]);
            $row = json_decode((string)$res->getBody(), true);
            $html = (string)($row['html'] ?? '');
            return [
                'id' => (int)$id,
                'type' => 'page',
                'url' => (string)($row['url'] ?? ''),
                'title' => (string)($row['title'] ?? ''),
                'excerpt' => Str::limit(strip_tags($html), 160),
                'html' => $html,
            ];
        }

        // crawler
        $urlsList = $this->listDocuments(['limit' => 100]);
        $match = collect($urlsList)->firstWhere('id', (int)$id);
        $targetUrl = $match['url'] ?? null;
        if (!$targetUrl) return ['id' => (int)$id, 'type' => 'page', 'url' => '', 'title' => '', 'excerpt' => '', 'html' => ''];

        $client = new Client(['timeout' => 20]);
        $res = $client->get($targetUrl);
        $html = (string)$res->getBody();

        // Grov title‑extraktion
        preg_match('/<title[^>]*>(.*?)<\/title>/is', $html, $m);
        $title = trim(html_entity_decode($m[1] ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'));

        return [
            'id' => (int)$id,
            'type' => 'page',
            'url' => $targetUrl,
            'title' => $title,
            'excerpt' => Str::limit(strip_tags($html), 160),
            'html' => $html,
        ];
    }

    public function updateDocument(int|string $id, string $type, array $payload): void
    {
        throw new \RuntimeException('update_meta stöds inte för Custom ännu');
    }

    public function publish(array $payload): array
    {
        throw new \RuntimeException('publish stöds inte för Custom ännu');
    }
}
