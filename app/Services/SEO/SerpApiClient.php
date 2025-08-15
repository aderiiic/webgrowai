<?php

namespace App\Services\SEO;

use GuzzleHttp\Client;

class SerpApiClient
{
    public function __construct(
        private string $apiKey,
        private string $engine = 'google',
        private string $hl = 'sv',
        private string $gl = 'se'
    ) {}

    private function http(): Client
    {
        return new Client(['base_uri' => 'https://serpapi.com/', 'timeout' => 30]);
    }

    public function search(string $query, array $params = []): array
    {
        $res = $this->http()->get('search.json', [
            'query' => array_merge([
                'api_key' => $this->apiKey,
                'engine'  => $this->engine,
                'q'       => $query,
                'hl'      => $this->hl,
                'gl'      => $this->gl,
                'num'     => 100,
            ], $params),
        ]);
        return json_decode((string) $res->getBody(), true);
    }
}
