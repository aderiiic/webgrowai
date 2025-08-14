<?php

namespace App\Jobs;

use App\Models\AiContent;
use App\Services\AI\AiProviderManager;
use App\Support\Usage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class GenerateContentJob implements ShouldQueue
{
    use Queueable;

    public function __construct(public int $aiContentId)
    {
        $this->onQueue('ai'); // undvik $queue-propertyn
    }

    public function handle(AiProviderManager $manager, Usage $usage): void
    {
        $content = AiContent::with('template')->findOrFail($this->aiContentId);

        $vars = [
            'title'    => $content->title,
            'audience' => $content->inputs['audience'] ?? null,
            'goal'     => $content->inputs['goal'] ?? null,
            'keywords' => $content->inputs['keywords'] ?? [],
            'brand'    => $content->inputs['brand'] ?? [],
        ];

        $prompt = view('prompts.'.$content->template->slug, $vars)->render();

        $provider = $manager->choose($content->template, $content->tone);

        try {
            $output = $provider->generate($prompt, [
                'temperature' => (float) ($content->template->temperature ?? 0.7),
                'max_tokens'  => (int) ($content->template->max_tokens ?? 1500),
            ]);

            $content->update([
                'provider' => $provider->name(),
                'body_md'  => $output,
                'status'   => 'ready',
                'error'    => null,
            ]);

            // Usage-tracking: AI-generering
            $usage->increment($content->customer_id, 'ai.generate');

        } catch (\Throwable $e) {
            $content->update([
                'status' => 'failed',
                'error'  => $e->getMessage(),
            ]);
            throw $e;
        }
    }
}
