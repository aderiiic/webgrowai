<?php

namespace App\Services\AI;

use App\Models\ContentTemplate;

class AiProviderManager
{
    public function __construct(
        private OpenAIProvider $openai,
        private AnthropicProvider $anthropic
    ) {}

    public function choose(?ContentTemplate $template, ?string $tone = null): AiProvider
    {
        if ($template && $template->provider !== 'auto') {
            return $template->provider === 'anthropic' ? $this->anthropic : $this->openai;
        }
        if ($tone === 'long') return $this->anthropic;
        return $this->openai;
    }
}
