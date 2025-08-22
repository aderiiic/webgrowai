<?php

namespace App\Livewire\AI;

use App\Jobs\PublishAiContentJob;
use App\Models\AiContent;
use App\Models\ContentPublication;
use App\Models\Integration;
use App\Services\Sites\IntegrationManager;
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
    // ... egenskaper som tidigare ...

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
            $iso = Carbon::parse($this->publishAt)->toIso8601String();
        }

        // Avgör preferred provider genom att titta på integrationerna för vald site
        $providers = Integration::where('site_id', $this->publishSiteId)->pluck('provider')->all();
        $preferredProvider = in_array('wordpress', $providers, true) ? 'wordpress'
            : (in_array('shopify', $providers, true) ? 'shopify' : null);

        $target = $preferredProvider === 'shopify' ? 'shopify' : 'wp';

        $pub = ContentPublication::create([
            'ai_content_id' => $this->content->id,
            'target'        => $target,
            'status'        => 'queued',
            'scheduled_at'  => $iso ? Carbon::parse($this->publishAt) : null,
            'message'       => null,
            'payload'       => [
                'site_id'  => $this->publishSiteId,
                'status'   => $this->publishStatus,
                'date'     => $iso,
                'provider' => $preferredProvider,
            ],
        ]);

        dispatch(new PublishAiContentJob(
            aiContentId: $this->content->id,
            siteId: $this->publishSiteId,
            status: $this->publishStatus,
            scheduleAtIso: $iso,
            publicationId: $pub->id,
            preferredProvider: $preferredProvider
        ))->onQueue('publish');

        $platform = $preferredProvider === 'shopify' ? 'Shopify' : 'WordPress';
        session()->flash('success', $platform.'-publicering köad.');
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

        $scheduledAt = $this->socialScheduleAt ? Carbon::parse($this->socialScheduleAt) : null;

        $pub = ContentPublication::create([
            'ai_content_id' => $this->content->id,
            'target'        => $this->socialTarget,
            'status'        => 'queued',
            'scheduled_at'  => $scheduledAt,
            'message'       => null,
            'payload'       => [
                'text' => $this->extractPlainText((string) ($this->content->body_md ?? '')),
            ],
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

        $md = $this->normalizeMd((string) ($this->content->body_md ?? ''));
        $html = $md !== '' ? Str::of($md)->markdown() : '';

        // Ta reda på provider för nuvarande vald sajt så vi kan visa rätt paneltext/ikon
        $currentProvider = null;
        try {
            if ($this->publishSiteId) {
                $currentProvider = app(IntegrationManager::class)->forSite((int)$this->publishSiteId)->provider();
            }
        } catch (\Throwable) {
            $currentProvider = null;
        }

        return view('livewire.a-i.detail', [
            'sites'           => $this->sites,
            'md'              => $md,
            'html'            => $html,
            'currentProvider' => $currentProvider, // 'shopify' | 'wordpress' | null
        ]);
    }

    private function extractPlainText(string $md): string
    {
        $txt = preg_replace('/[`*_>#-]+/', '', $md ?? '');
        $txt = strip_tags((string) $txt);
        return trim((string) $txt);
    }

    private function normalizeMd(string $md): string
    {
        if ($md === '') return $md;
        $md = str_replace(["\r\n", "\r"], "\n", $md);

        $trimmed = trim($md);
        if (str_starts_with($trimmed, '```') && str_ends_with($trimmed, '```')) {
            $trimmed = preg_replace('/^```[a-zA-Z0-9_-]*\n?/','', $trimmed);
            $trimmed = preg_replace("/\n?```$/", '', $trimmed);
            $md = $trimmed;
        }

        $lines = explode("\n", $md);
        $minIndent = null;
        foreach ($lines as $line) {
            if (trim($line) === '') continue;
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
                $line = str_replace("\t", '    ', $line);
                return preg_replace('/^ {0,' . $minIndent . '}/', '', $line);
            }, $lines));
        }

        return trim($md);
    }
}
