<?php

namespace App\Livewire\AI;

use App\Jobs\GenerateContentJob;
use App\Models\AiContent;
use App\Models\ContentTemplate;
use App\Support\CurrentCustomer;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class Compose extends Component
{
    public ?int $template_id = null;
    public string $title = '';
    public string $tone = 'short'; // short|long
    public ?int $site_id = null;

    public string $audience = '';
    public string $goal = '';
    public string $keywords = '';
    public string $brand_voice = '';

    public function submit(CurrentCustomer $current)
    {
        $this->validate([
            'template_id' => 'required|exists:content_templates,id',
            'title' => 'required|string|min:3',
            'tone' => 'required|in:short,long',
            'site_id' => 'nullable|exists:sites,id',
        ]);

        $customer = $current->get();
        abort_unless($customer, 403);

        $inputs = [
            'audience' => $this->audience ?: null,
            'goal'     => $this->goal ?: null,
            'keywords' => $this->keywords ? array_values(array_filter(array_map('trim', explode(',', $this->keywords)))) : [],
            'brand'    => ['voice' => $this->brand_voice ?: null],
        ];

        $content = AiContent::create([
            'customer_id' => $customer->id,
            'site_id'     => $this->site_id,
            'template_id' => $this->template_id,
            'title'       => $this->title,
            'tone'        => $this->tone,
            'status'      => 'queued',
            'inputs'      => $inputs,
        ]);

        dispatch(new GenerateContentJob($content->id))->onQueue('ai');

        return redirect()->route('ai.detail', $content->id)
            ->with('success', 'Generering påbörjad.');
    }

    public function render(CurrentCustomer $current)
    {
        $templates = ContentTemplate::orderBy('name')->get();
        $sites = $current->get()?->sites()->orderBy('name')->get() ?? collect();

        return view('livewire.a-i.compose', compact('templates','sites'));
    }
}
