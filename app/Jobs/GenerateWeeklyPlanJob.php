<?php

namespace App\Jobs;

use App\Models\{AiContent, ContentTemplate, Customer};
use App\Services\AI\AiProviderManager;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class GenerateWeeklyPlanJob implements ShouldQueue
{
    use Queueable;

    public $queue = 'ai';

    public function __construct(public int $customerId) {}

    public function handle(AiProviderManager $manager): void
    {
        $customer = Customer::findOrFail($this->customerId);

        $template = ContentTemplate::firstOrCreate(
            ['slug' => 'campaign'],
            ['name' => 'Kampanjidéer', 'provider' => 'openai', 'max_tokens' => 1000, 'temperature' => 0.7, 'visibility' => 'system']
        );

        $content = AiContent::create([
            'customer_id' => $customer->id,
            'site_id'     => null,
            'template_id' => $template->id,
            'title'       => 'Veckoplanering: kommande idéer',
            'tone'        => 'short',
            'status'      => 'queued',
            'inputs'      => [
                'audience' => 'Befintliga och potentiella kunder',
                'goal'     => 'Fler leads nästa vecka',
                'brand'    => ['voice' => 'hjälpsam, tydlig, handlingsinriktad'],
            ],
        ]);

        dispatch(new GenerateContentJob($content->id))->onQueue('ai');
    }
}
