<?php

namespace App\Services;

use App\Models\WpIntegration;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
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
            'auth'     => [$username, $appPassword],
            'timeout'  => 20,
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

    public function getMe(): array
    {
        $res = $this->client->get('/wp-json/wp/v2/users/me', ['query' => ['context' => 'edit']]);
        return json_decode((string) $res->getBody(), true);
    }

    public function getPosts(array $params = []): array
    {
        $query = array_merge(['per_page' => 10, 'status' => 'any', 'orderby' => 'date', 'order' => 'desc'], $params);
        $res = $this->client->get('/wp-json/wp/v2/posts', ['query' => $query]);
        return json_decode((string) $res->getBody(), true);
    }

    public function createPost(array $payload): array
    {
        try {
            $res = $this->client->post('/wp-json/wp/v2/posts', ['json' => $payload]);
            return json_decode((string) $res->getBody(), true);
        } catch (ClientException $e) {
            $body = (string) ($e->getResponse()?->getBody() ?? '');
            throw new \RuntimeException('WP createPost misslyckades: '.$body, $e->getCode(), $e);
        }
    }

    public function updatePost(int $id, array $payload): array
    {
        try {
            $res = $this->client->post("/wp-json/wp/v2/posts/{$id}", ['json' => $payload]);
            return json_decode((string) $res->getBody(), true);
        } catch (ClientException $e) {
            $body = (string) ($e->getResponse()?->getBody() ?? '');
            throw new \RuntimeException('WP updatePost misslyckades: '.$body, $e->getCode(), $e);
        }
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
        try {
            $res = $this->client->post("/wp-json/wp/v2/pages/{$id}", ['json' => $payload]);
            return json_decode((string) $res->getBody(), true);
        } catch (ClientException $e) {
            $body = (string) ($e->getResponse()?->getBody() ?? '');
            throw new \RuntimeException('WP updatePage misslyckades: '.$body, $e->getCode(), $e);
        }
    }

    public function uploadMedia(string $bytes, string $filename, string $mime = 'image/png'): array
    {
        $res = $this->client->post('/wp-json/wp/v2/media', [
            'timeout'         => 120,
            'connect_timeout' => 10,
            'expect'          => false,
            'multipart' => [
                [
                    'name'     => 'file',
                    'contents' => $bytes,
                    'filename' => $filename,
                    'headers'  => ['Content-Type' => $mime],
                ],
            ],
        ]);
        return json_decode((string) $res->getBody(), true);
    }
}
