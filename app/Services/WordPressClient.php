<?php

namespace App\Services;

use App\Models\WpIntegration;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Crypt;

class WordPressClient
{
    private Client $client;
    private string $baseUrl;

    public function __construct(string $baseUrl, string $username, string $appPassword)
    {
        $this->baseUrl = rtrim($baseUrl, '/');
        $this->client = new Client([
            'base_uri' => $this->baseUrl,
            'auth' => [$username, $appPassword],
            'timeout' => 20,
        ]);
    }

    public static function for(WpIntegration $integration): self
    {
        return new self(
            $integration->wp_url,
            $integration->wp_username,
            Crypt::decryptString($integration->wp_app_password)
        );
    }

    public function getPosts(array $params = []): array
    {
        $query = array_merge(['per_page' => 10, 'status' => 'any', 'orderby' => 'date', 'order' => 'desc'], $params);
        $res = $this->client->get('/wp-json/wp/v2/posts', ['query' => $query]);
        return json_decode((string) $res->getBody(), true);
    }

    public function createPost(array $payload): array
    {
        $res = $this->client->post('/wp-json/wp/v2/posts', ['json' => $payload]);
        return json_decode((string) $res->getBody(), true);
    }

    public function updatePost(int $id, array $payload): array
    {
        // WP REST API accepterar POST för uppdatering
        $res = $this->client->post("/wp-json/wp/v2/posts/{$id}", ['json' => $payload]);
        return json_decode((string) $res->getBody(), true);
    }

    public function getPost(int $id): array
    {
        $res = $this->client->get("/wp-json/wp/v2/posts/{$id}");
        return json_decode((string) $res->getBody(), true);
    }

    public function getPages(array $params = []): array
    {
        $query = array_merge(['per_page' => 10, 'status' => 'publish', 'orderby' => 'date', 'order' => 'desc'], $params);
        $res = $this->client->get('/wp-json/wp/v2/pages', ['query' => $query]);
        return json_decode((string) $res->getBody(), true);
    }

    public function getPage(int $id): array
    {
        $res = $this->client->get("/wp-json/wp/v2/pages/{$id}");
        return json_decode((string) $res->getBody(), true);
    }

    public function updatePage(int $id, array $payload): array
    {
        $res = $this->client->post("/wp-json/wp/v2/pages/{$id}", ['json' => $payload]);
        return json_decode((string) $res->getBody(), true);
    }
}
