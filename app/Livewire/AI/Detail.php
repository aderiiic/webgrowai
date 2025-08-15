<?php

namespace App\Livewire\AI;

use App\Jobs\PublishAiContentToWpJob;
use App\Jobs\PublishToFacebookJob;
use App\Jobs\PublishToInstagramJob;
use App\Models\AiContent;
use App\Models\ContentPublication;
use App\Support\CurrentCustomer;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class Detail extends Component
{
    public AiContent $content;

    // WP publicering
    public ?int $publishSiteId = null;
    public string $publishStatus = 'draft'; // draft|publish|future
    public ?string $publishAt = null; // 'YYYY-MM-DDTHH:MM'

    // Social publicering
    public string $socialTarget = 'facebook'; // facebook|instagram
    public ?string $socialScheduleAt = null;  // 'YYYY-MM-DDTHH:MM'

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

        session()->flash('success', 'WP-publicering köad.');
    }

    public function quickDraft(CurrentCustomer $current): void
    {
        $this->publishStatus = 'draft';
        $this->publishAt = null;
        $this->publish($current);
    }

    public function queueSocial(): void
    {
        Gate::authorize('update', $this->content);
        $this->validate([
            'socialTarget' => 'required|in:facebook,instagram',
            'socialScheduleAt' => 'nullable|date',
        ]);

        $scheduledAt = $this->socialScheduleAt ? \Illuminate\Support\Carbon::parse($this->socialScheduleAt) : null;

        $pub = ContentPublication::create([
            'ai_content_id' => $this->content->id,
            'target'        => $this->socialTarget,
            'status'        => 'queued',
            'scheduled_at'  => $scheduledAt,
            'message'       => null,
            'payload'       => null,
        ]);

        // Kör direkt om ingen framtida tid
        if (!$scheduledAt || $scheduledAt->isPast()) {
            if ($this->socialTarget === 'facebook') {
                dispatch(new PublishToFacebookJob($pub->id))->onQueue('social');
            } else {
                dispatch(new PublishToInstagramJob($pub->id))->onQueue('social');
            }
            session()->flash('success', ucfirst($this->socialTarget).' publicering startad.');
        } else {
            session()->flash('success', ucfirst($this->socialTarget)." schemalagd till {$scheduledAt->format('Y-m-d H:i')}.");
        }
    }
}
