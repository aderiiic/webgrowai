<?php
// app/Services/Sites/Providers/ShopifyAdapter.php

namespace App\Services\Sites\Providers;

use App\Models\Integration;
use App\Services\Sites\SiteIntegrationClient;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;

class ShopifyAdapter implements SiteIntegrationClient
{
    public function __construct(private Integration $integration) {}

    public function provider(): string { return 'shopify'; }

    public function supports(string $capability): bool
    {
        // Första versionen: endast läsning för analys
        return in_array($capability, ['list','fetch'], true);
    }

    private function http(): Client
    {
        $creds  = $this->integration->credentials ?? [];
        $domain = rtrim((string)($creds['shop_domain'] ?? ''), '/');

        // Viktigt: dekryptera token om den är krypterad
        $rawToken = (string)($creds['access_token'] ?? '');
        try {
            $token = $rawToken !== '' ? Crypt::decryptString($rawToken) : '';
        } catch (\Throwable) {
            // Om det redan är plaintext, använd som är
            $token = $rawToken;
        }

        if ($domain === '' || $token === '') {
            throw new \RuntimeException('Ogiltiga Shopify-uppgifter (domain/token saknas).');
        }

        $version = config('services.shopify.api_version', '2024-10');

        return new Client([
            'base_uri' => "https://{$domain}/admin/api/{$version}/",
            'timeout'  => 30,
            'headers'  => [
                'X-Shopify-Access-Token' => $token,
                'Content-Type'           => 'application/json',
                'Accept'                 => 'application/json',
            ],
        ]);
    }

    public function listDocuments(array $opts = []): array
    {
        $limit = (int)($opts['limit'] ?? 10);
        $http  = $this->http();

        $out = [];

        if (count($out) < $limit) {
            $res  = $http->get('pages.json', ['query' => ['limit' => min(10, $limit)]]);
            $json = json_decode((string)$res->getBody(), true);
            foreach (($json['pages'] ?? []) as $p) {
                $out[] = [
                    'id'     => (int)$p['id'],
                    'type'   => 'page',
                    'url'    => (string)($p['handle'] ? "https://{$this->integration->credentials['shop_domain']}/pages/{$p['handle']}" : ''),
                    'title'  => (string)($p['title'] ?? ''),
                    'excerpt'=> Str::limit(strip_tags((string)($p['body_html'] ?? '')), 160),
                ];
                if (count($out) >= $limit) break;
            }
        }

        if (count($out) < $limit) {
            $blogsRes = $http->get('blogs.json', ['query' => ['limit' => 1]]);
            $blogs    = json_decode((string)$blogsRes->getBody(), true);
            $blogId   = $blogs['blogs'][0]['id'] ?? null;
            if ($blogId) {
                $res  = $http->get("blogs/{$blogId}/articles.json", ['query' => ['limit' => min(10, $limit - count($out))]]);
                $json = json_decode((string)$res->getBody(), true);
                foreach (($json['articles'] ?? []) as $a) {
                    $out[] = [
                        'id'     => (int)$a['id'],
                        'type'   => 'article',
                        'url'    => (string)($a['url'] ?? ''),
                        'title'  => (string)($a['title'] ?? ''),
                        'excerpt'=> Str::limit(strip_tags((string)($a['body_html'] ?? '')), 160),
                    ];
                    if (count($out) >= $limit) break;
                }
            }
        }

        return $out;
    }


    public function getDocument(int|string $id, string $type): array
    {
        $http = $this->http();

        if ($type === 'page') {
            $res = $http->get("pages/{$id}.json");
            $p   = (json_decode((string)$res->getBody(), true))['page'] ?? [];
            $html = (string)($p['body_html'] ?? '');
            return [
                'id' => (int)($p['id'] ?? $id),
                'type' => 'page',
                'url' => (string)($p['handle'] ? "https://{$this->integration->credentials['shop_domain']}/pages/{$p['handle']}" : ''),
                'title' => (string)($p['title'] ?? ''),
                'excerpt'=> Str::limit(strip_tags($html), 160),
                'html' => $html,
            ];
        }

        if ($type === 'article') {
            $blogsRes = $http->get('blogs.json', ['query' => ['limit' => 5]]);
            $blogs = json_decode((string)$blogsRes->getBody(), true);
            foreach (($blogs['blogs'] ?? []) as $b) {
                $res  = $http->get("blogs/{$b['id']}/articles.json", ['query' => ['limit' => 50]]);
                $json = json_decode((string)$res->getBody(), true);
                foreach (($json['articles'] ?? []) as $a) {
                    if ((int)$a['id'] === (int)$id) {
                        $html = (string)($a['body_html'] ?? '');
                        return [
                            'id' => (int)$a['id'],
                            'type' => 'article',
                            'url' => (string)($a['url'] ?? ''),
                            'title' => (string)($a['title'] ?? ''),
                            'excerpt'=> Str::limit(strip_tags($html), 160),
                            'html' => $html,
                        ];
                    }
                }
            }
        }

        return ['id' => (int)$id, 'type' => $type, 'url' => '', 'title' => '', 'excerpt' => '', 'html' => ''];
    }

    public function updateDocument(int|string $id, string $type, array $payload): void
    {
        throw new \RuntimeException('update_meta stöds inte för Shopify ännu');
    }

    public function publish(array $payload): array
    {
        throw new \RuntimeException('publish stöds inte för Shopify ännu');
    }
}
