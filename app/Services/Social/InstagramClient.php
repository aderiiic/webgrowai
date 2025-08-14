<?php

namespace App\Services\Social;

use GuzzleHttp\Client;

class InstagramClient
{
    public function __construct(private string $accessToken) {}

    private function http(): Client
    {
        return new Client(['base_uri' => 'https://graph.facebook.com/v19.0/', 'timeout' => 30]);
    }

    // Skapa container med bild-URL + caption, sedan publicera
    public function createImageContainer(string $igUserId, string $imageUrl, string $caption): array
    {
        $res = $this->http()->post($igUserId.'/media', [
            'query' => ['access_token' => $this->accessToken],
            'form_params' => [
                'image_url' => $imageUrl,
                'caption'   => $caption,
            ],
        ]);
        return json_decode((string) $res->getBody(), true);
    }

    public function publishContainer(string $igUserId, string $creationId): array
    {
        $res = $this->http()->post($igUserId.'/media_publish', [
            'query' => ['access_token' => $this->accessToken],
            'form_params' => ['creation_id' => $creationId],
        ]);
        return json_decode((string) $res->getBody(), true);
    }
}
