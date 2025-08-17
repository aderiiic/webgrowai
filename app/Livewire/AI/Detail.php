<?php

namespace App\Livewire\AI;

use App\Jobs\PublishAiContentToWpJob;
use App\Jobs\PublishToFacebookJob;
use App\Jobs\PublishToInstagramJob;
use App\Jobs\PublishToLinkedInJob;
use App\Models\AiContent;
use App\Models\ContentPublication;
use App\Support\CurrentCustomer;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class Detail extends Component
{
    public AiContent $content;
    public $sites;

    public ?int $publishSiteId = null;
    public string $publishStatus = 'draft';
    public ?string $publishAt = null;

    public string $socialTarget = '';
    public ?string $socialScheduleAt = null;

    public string $liQuickText = '';
    public ?string $liQuickScheduleAt = null;
    public string $liQuickImagePrompt = '';

    public function mount(int $id, CurrentCustomer $current): void
    {
        $this->content = AiContent::with('template','site','customer')->findOrFail($id);
        Gate::authorize('view', $this->content);

        $this->sites = $current->get()?->sites()->orderBy('name')->get() ?? collect();
        $this->publishSiteId = $this->content->site_id ?: ($current->get()?->sites()->orderBy('id')->value('id'));
    }

    public function publish(): void
    {
        Gate::authorize('update', $this->content);

        $this->validate([
            'publishSiteId' => 'required|integer|exists:sites,id',
            'publishStatus' => 'required|in:draft,publish,future',
            'publishAt'     => 'nullable|date',
        ]);

        $current = app(CurrentCustomer::class);
        $user = auth()->user();
        if (!$user->isAdmin()) {
            $customer = $current->get();
            abort_unless($customer && $customer->sites()->whereKey($this->publishSiteId)->exists(), 403);
        }

        $iso = null;
        if ($this->publishStatus === 'future' && $this->publishAt) {
            $iso = \Illuminate\Support\Carbon::parse($this->publishAt)->toIso8601String();
        }

        $pub = ContentPublication::create([
            'ai_content_id' => $this->content->id,
            'target'        => 'wp',
            'status'        => 'queued',
            'scheduled_at'  => $iso ? \Illuminate\Support\Carbon::parse($this->publishAt) : null,
            'message'       => null,
            'payload'       => [
                'site_id' => $this->publishSiteId,
                'status'  => $this->publishStatus,
                'date'    => $iso,
            ],
        ]);

        dispatch(new PublishAiContentToWpJob(
            aiContentId: $this->content->id,
            siteId: $this->publishSiteId,
            status: $this->publishStatus,
            scheduleAtIso: $iso,
            publicationId: $pub->id
        ))->onQueue('publish');

        session()->flash('success', 'WP-publicering köad.');
    }

    public function quickDraft(): void
    {
        $this->publishStatus = 'draft';
        $this->publishAt = null;
        $this->publish();
    }

    public function queueSocial(): void
    {
        Gate::authorize('update', $this->content);
        $this->validate([
            'socialTarget' => 'required|in:facebook,instagram,linkedin',
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

        if (!$scheduledAt || $scheduledAt->isPast()) {
            if ($this->socialTarget === 'facebook') {
                dispatch(new PublishToFacebookJob($pub->id))->onQueue('social');
            } elseif ($this->socialTarget === 'instagram') {
                dispatch(new PublishToInstagramJob($pub->id))->onQueue('social');
            } else {
                dispatch(new PublishToLinkedInJob($pub->id))->onQueue('social');
            }
            session()->flash('success', ucfirst($this->socialTarget).' publicering startad.');
        } else {
            session()->flash('success', ucfirst($this->socialTarget)." schemalagd till {$scheduledAt->format('Y-m-d H:i')}.");
        }
    }

    public function queueLinkedInQuick(): void
    {
        Gate::authorize('update', $this->content);

        $this->validate([
            'liQuickText' => 'required|string',
            'liQuickScheduleAt' => 'nullable|date',
            'liQuickImagePrompt' => 'nullable|string|max:500',
        ]);

        $scheduledAt = $this->liQuickScheduleAt ? Carbon::parse($this->liQuickScheduleAt) : null;

        $pub = ContentPublication::create([
            'ai_content_id' => $this->content->id,
            'target'        => 'linkedin',
            'status'        => 'queued',
            'scheduled_at'  => $scheduledAt,
            'message'       => null,
            'payload'       => [
                'text' => $this->liQuickText,
                'image_prompt' => $this->liQuickImagePrompt ?: null,
            ],
        ]);

        if (!$scheduledAt || $scheduledAt->isPast()) {
            dispatch(new PublishToLinkedInJob($pub->id))->onQueue('social');
            session()->flash('success', 'LinkedIn publicering startad.');
        } else {
            session()->flash('success', "LinkedIn schemalagd till {$scheduledAt->format('Y-m-d H:i')}.");
        }
    }

    public function render(): View
    {
        $this->content->refresh();

        $md = $this->content->body_md ?? '';
        $normalized = $this->normalizeMd($md);
        $html = $normalized !== '' ? Str::of($normalized)->markdown() : '';

        return view('livewire.a-i.detail', [
            'sites' => $this->sites,
            'md'    => $normalized,
            'html'  => $html,
        ]);
    }

    /**
     * Tar bort oavsiktlig indentering och inledande/avslutande kodstaket som gör att Markdown blir kodblock.
     */
    private function normalizeMd(string $md): string
    {
        if ($md === '') {
            return $md;
        }

        // Normalisera radbrytningar
        $md = str_replace(["\r\n", "\r"], "\n", $md);

        // Trimma bort inledande och avslutande "```" om hela texten råkat hamna i kodstaket
        $trimmed = trim($md);
        if (str_starts_with($trimmed, '```') && str_ends_with($trimmed, '```')) {
            $trimmed = preg_replace('/^```[a-zA-Z0-9_-]*\n?/','', $trimmed);
            $trimmed = preg_replace("/\n?```$/", '', $trimmed);
            $md = $trimmed;
        }

        // Ta bort gemensam minsta indentering (dedent) för att undvika att allt blir code block
        $lines = explode("\n", $md);
        $minIndent = null;
        foreach ($lines as $line) {
            if (trim($line) === '') {
                continue;
            }
            preg_match('/^( +|\t+)/', $line, $m);
            if (!empty($m[0])) {
                $len = strlen(str_replace("\t", '    ', $m[0]));
                $minIndent = $minIndent === null ? $len : min($minIndent, $len);
            } else {
                $minIndent = 0;
                break;
            }
        }
        if ($minIndent && $minIndent > 0) {
            $md = implode("\n", array_map(function ($line) use ($minIndent) {
                // ersätt tabs med 4 spaces för säker dedent
                $line = str_replace("\t", '    ', $line);
                return preg_replace('/^ {0,' . $minIndent . '}/', '', $line);
            }, $lines));
        }

        return trim($md);
    }
}
