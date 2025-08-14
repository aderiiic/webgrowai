<?php

namespace App\Livewire\AI;

use App\Jobs\PublishAiContentToWpJob;
use App\Models\AiContent;
use App\Support\CurrentCustomer;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class Detail extends Component
{
    public AiContent $content;

    public ?int $publishSiteId = null;
    public string $publishStatus = 'draft'; // draft|publish|future
    public ?string $publishAt = null; // 'YYYY-MM-DDTHH:MM'

    public function mount(int $id, CurrentCustomer $current): void
    {
        $this->content = AiContent::with('template','site','customer')->findOrFail($id);
        Gate::authorize('view', $this->content);

        $this->publishSiteId = $this->content->site_id ?: ($current->get()?->sites()->orderBy('id')->value('id'));
    }

    public function publish(CurrentCustomer $current): void
    {
        Gate::authorize('update', $this->content);

        $this->validate([
            'publishSiteId' => 'required|integer|exists:sites,id',
            'publishStatus' => 'required|in:draft,publish,future',
            'publishAt'     => 'nullable|date',
        ]);

        $user = auth()->user();
        if (!$user->isAdmin()) {
            $customer = $current->get();
            abort_unless($customer && $customer->sites()->whereKey($this->publishSiteId)->exists(), 403);
        }

        $iso = null;
        if ($this->publishStatus === 'future' && $this->publishAt) {
            $iso = \Illuminate\Support\Carbon::parse($this->publishAt)->toIso8601String();
        }

        dispatch(new PublishAiContentToWpJob(
            aiContentId: $this->content->id,
            siteId: $this->publishSiteId,
            status: $this->publishStatus,
            scheduleAtIso: $iso
        ))->onQueue('publish');

        session()->flash('success', 'Publicering köad till WordPress.');
    }

    public function quickDraft(CurrentCustomer $current): void
    {
        // Snabb “Publicera som utkast”
        $this->publishStatus = 'draft';
        $this->publishAt = null;
        $this->publish($current);
    }

    public function render(CurrentCustomer $current)
    {
        $sites = $current->get()?->sites()->orderBy('name')->get() ?? collect();
        return view('livewire.ai.detail', [
            'md' => $this->content->body_md,
            'sites' => $sites,
        ]);
    }
}
