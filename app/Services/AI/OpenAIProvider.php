<?php

namespace App\Services\AI;

use GuzzleHttp\Client;

class OpenAIProvider implements AiProvider
{
    public function __construct(private Client $http) {}

    public function generate(string $prompt, array $options = []): string
    {
        $model = $options['model'] ?? config('services.openai.model', 'gpt-4o-mini');
        $res = $this->http->post('https://api.openai.com/v1/chat/completions', [
            'headers' => ['Authorization' => 'Bearer '.config('services.openai.key')],
            'json' => [
                'model' => $model,
                'messages' => [
                    ['role' => 'system', 'content' => 'Du är en svensk marknadsföringsassistent.'],
                    ['role' => 'user', 'content' => $prompt],
                ],
                'temperature' => $options['temperature'] ?? 0.7,
                'max_tokens' => $options['max_tokens'] ?? 1200,
            ],
            'timeout' => 60,
        ]);
        $data = json_decode((string) $res->getBody(), true);
        return $data['choices'][0]['message']['content'] ?? '';
    }

    public function name(): string { return 'openai'; }
}
