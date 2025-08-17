<?php

namespace App\Services\Social;

use GuzzleHttp\Client;

class LinkedInService
{
    private Client $http;

    public function __construct()
    {
        $this->http = new Client(['base_uri' => 'https://api.linkedin.com/v2/', 'timeout' => 30]);
    }

    public function registerImageUpload(string $accessToken, string $ownerUrn): array
    {
        $payload = [
            'registerUploadRequest' => [
                'owner' => $ownerUrn,
                'recipes' => ['urn:li:digitalmediaRecipe:feedshare-image'],
                'serviceRelationships' => [[
                    'relationshipType' => 'OWNER',
                    'identifier' => 'urn:li:userGeneratedContent',
                ]],
                'supportedUploadMechanism' => ['SYNCHRONOUS_UPLOAD'],
            ],
        ];

        $res = $this->http->post('assets?action=registerUpload', [
            'headers' => [
                'Authorization' => "Bearer {$accessToken}",
                'Content-Type' => 'application/json',
            ],
            'json' => $payload,
        ]);

        $data = json_decode((string)$res->getBody(), true);
        return [
            'uploadUrl' => $data['value']['uploadMechanism']['com.linkedin.digitalmedia.uploading.MediaUploadHttpRequest']['uploadUrl'] ?? null,
            'asset'     => $data['value']['asset'] ?? null,
        ];
    }

    public function uploadToLinkedInUrl(string $uploadUrl, string $bytes, string $mime = 'image/png'): void
    {
        (new Client(['timeout' => 60]))->put($uploadUrl, [
            'headers' => ['Content-Type' => $mime],
            'body'    => $bytes,
        ]);
    }

    public function publishPost(string $accessToken, string $ownerUrn, string $text, ?string $assetUrn = null): array
    {
        $payload = [
            'author' => $ownerUrn,
            'lifecycleState' => 'PUBLISHED',
            'specificContent' => [
                'com.linkedin.ugc.ShareContent' => [
                    'shareCommentary' => ['text' => $text],
                    'shareMediaCategory' => $assetUrn ? 'IMAGE' : 'NONE',
                    'media' => $assetUrn ? [[
                        'status' => 'READY',
                        'media'  => $assetUrn,
                    ]] : [],
                ],
            ],
            'visibility' => [
                'com.linkedin.ugc.MemberNetworkVisibility' => 'PUBLIC',
            ],
        ];

        $res = $this->http->post('ugcPosts', [
            'headers' => [
                'Authorization' => "Bearer {$accessToken}",
                'Content-Type' => 'application/json',
                'X-Restli-Protocol-Version' => '2.0.0',
            ],
            'json' => $payload,
        ]);

        return json_decode((string)$res->getBody(), true);
    }
}
