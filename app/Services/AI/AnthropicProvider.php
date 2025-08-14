<?php

namespace App\Services\AI;

use GuzzleHttp\Client;

class AnthropicProvider implements AiProvider
{
    public function __construct(private Client $http) {}

    public function generate(string $prompt, array $options = []): string
    {
        $model = $options['model'] ?? config('services.anthropic.model', 'claude-3-5-sonnet-latest');
        $res = $this->http->post('https://api.anthropic.com/v1/messages', [
            'headers' => [
                'x-api-key' => config('services.anthropic.key'),
                'anthropic-version' => config('services.anthropic.version', '2023-06-01'),
            ],
            'json' => [
                'model' => $model,
                'system' => 'Du är en svensk senior copywriter och SEO-strateg.',
                'messages' => [['role' => 'user', 'content' => $prompt]],
                'max_tokens' => $options['max_tokens'] ?? 3000,
                'temperature' => $options['temperature'] ?? 0.7,
            ],
            'timeout' => 90,
        ]);
        $data = json_decode((string) $res->getBody(), true);
        return $data['content'][0]['text'] ?? '';
    }

    public function name(): string { return 'anthropic'; }
}
