<?php

namespace App\Services\Social;

use GuzzleHttp\Client;

class FacebookClient
{
    public function __construct(private string $accessToken) {}

    private function http(): Client
    {
        return new Client(['base_uri' => 'https://graph.facebook.com/v19.0/', 'timeout' => 30]);
    }

    public function createPagePost(string $pageId, string $message): array
    {
        $res = $this->http()->post($pageId.'/feed', [
            'query' => ['access_token' => $this->accessToken],
            'form_params' => ['message' => $message],
        ]);
        return json_decode((string) $res->getBody(), true);
    }

    // MVP: schemalägg via scheduled_publish_time + published=false
    public function schedulePagePost(string $pageId, string $message, int $timestamp): array
    {
        $res = $this->http()->post($pageId.'/feed', [
            'query' => ['access_token' => $this->accessToken],
            'form_params' => [
                'message' => $message,
                'published' => 'false',
                'scheduled_publish_time' => $timestamp,
            ],
        ]);
        return json_decode((string) $res->getBody(), true);
    }

    public function createPagePhoto(string $pageId, string $bytes, string $filename, string $caption = ''): array
    {
        $res = $this->http()->post($pageId.'/photos', [
            'query' => ['access_token' => $this->accessToken],
            'multipart' => [
                [
                    'name'     => 'source',
                    'contents' => $bytes,
                    'filename' => $filename,
                ],
                [
                    'name'     => 'caption',
                    'contents' => $caption,
                ],
                [
                    'name'     => 'published',
                    'contents' => 'true',
                ],
            ],
        ]);
        return json_decode((string) $res->getBody(), true);
    }
}
