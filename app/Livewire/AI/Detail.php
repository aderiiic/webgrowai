<?php

namespace App\Livewire\AI;

use App\Jobs\GenerateContentJob;
use App\Jobs\PublishAiContentJob;
use App\Jobs\PublishToFacebookJob;
use App\Jobs\PublishToInstagramJob;
use App\Jobs\PublishToLinkedInJob;
use App\Models\AiContent;
use App\Models\ContentPublication;
use App\Services\Sites\IntegrationManager;
use App\Support\CurrentCustomer;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;

#[Layout('layouts.app')]
class Detail extends Component
{
    public AiContent $content;
    public $sites;

    public bool $genShow = false;
    public string $genFormat = 'square'; // square|portrait|landscape
    public bool $genBusy = false;
    public bool $genQueued = false;
    public ?int $genRemaining = null;
    public ?int $genAfter = null;
    public string $genError = '';

    public ?int $publishSiteId = null;
    public string $publishStatus = 'draft';
    public ?string $publishAt = null;

    public string $socialTarget = '';
    public ?string $socialScheduleAt = null;

    public string $liQuickText = '';
    public ?string $liQuickScheduleAt = null;

    // Bildbank (endast)
    public int $selectedImageAssetId = 0;

    // UI-hjälp: om kvot är nådd (beräknas i render)
    public bool $publishQuotaReached = false;
    public ?int $publishQuotaLimit = null;
    public int $publishQuotaUsed = 0;

    // Inline-redigering
    public bool $isEditing = false;
    public string $editTitle = '';
    public string $editBody = '';

    #[On('media-selected')]
    public function setSelectedImage(int $id): void
    {
        $this->selectedImageAssetId = $id;
        session()->flash('success', "Bild vald: #{$id}");
    }

    public function mount(int $id, CurrentCustomer $current): void
    {
        $this->content = AiContent::with('template','site','customer')->findOrFail($id);
        Gate::authorize('view', $this->content);

        $this->sites = $current->get()?->sites()->orderBy('name')->get() ?? collect();
        $this->publishSiteId = $this->content->site_id ?: ($current->get()?->sites()->orderBy('id')->value('id'));

        if (empty($this->socialTarget)) {
            $this->socialTarget = 'facebook';
        }
    }

    // ----- Inline-redigering -----

    public function startEditing(): void
    {
        Gate::authorize('update', $this->content);

        if ($this->isLocked()) {
            session()->flash('error', 'Texten är redan publicerad och kan inte redigeras.');
            return;
        }

        $this->editTitle = (string) ($this->content->title ?? '');
        $this->editBody  = (string) ($this->content->body_md ?? '');
        $this->isEditing = true;
    }

    public function cancelEditing(): void
    {
        $this->isEditing = false;
    }

    public function saveEdits(): void
    {
        Gate::authorize('update', $this->content);

        if ($this->isLocked()) {
            session()->flash('error', 'Texten är redan publicerad och kan inte redigeras.');
            return;
        }

        $this->validate([
            'editTitle' => 'nullable|string|max:200',
            'editBody'  => 'nullable|string|max:20000',
        ]);

        $normalized = $this->normalizeMd($this->editBody ?? '');

        $this->content->update([
            'title'   => $this->editTitle !== '' ? $this->editTitle : $this->content->title,
            'body_md' => $normalized !== '' ? $normalized : null,
            // status lämnas oförändrad (t.ex. 'ready')
        ]);

        $this->isEditing = false;
        session()->flash('success', 'Texten sparades.');
    }

    private function isLocked(): bool
    {
        // Låst om publicerad (eller om modellen har ett explicit lås)
        return $this->content->publications()->where('status', 'published')->exists()
            || (bool) ($this->content->is_locked ?? false);
    }

    // ----- Publicering & socialt (oförändrat) -----

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
        } else {
            $customer = $current->get();
        }

        // Kvot
        $limit = null;
        try { $limit = optional(optional($customer)->plan)->publication_quota; } catch (\Throwable) { $limit = null; }
        if ($limit !== null) {
            $start = Carbon::now()->startOfMonth();
            $end   = Carbon::now()->endOfMonth();
            $used = ContentPublication::whereBetween('created_at', [$start, $end])
                ->whereHas('aiContent', fn($q) => $q->when($customer, fn($q2) => $q2->where('customer_id', $customer->id)))
                ->count();
            if ($used >= (int)$limit) {
                session()->flash('success', 'Kvotgräns för publiceringar är uppnådd för din plan.');
                return;
            }
        }

        $iso = null;
        if ($this->publishStatus === 'future' && $this->publishAt) {
            $iso = Carbon::parse($this->publishAt)->toIso8601String();
        }

        $client = app(IntegrationManager::class)->forSite($this->publishSiteId);
        $provider = $client->provider();
        $target = $provider;

        $payload = [
            'site_id' => $this->publishSiteId,
            'status'  => $this->publishStatus,
            'date'    => $iso,
        ];

        if ($this->selectedImageAssetId > 0) {
            $payload['image_asset_id'] = (int)$this->selectedImageAssetId;
        }

        $pub = ContentPublication::create([
            'ai_content_id' => $this->content->id,
            'target'        => $target,
            'status'        => 'queued',
            'scheduled_at'  => $iso ? Carbon::parse($this->publishAt) : null,
            'message'       => null,
            'payload'       => $payload,
        ]);

        dispatch(new PublishAiContentJob(
            aiContentId: $this->content->id,
            siteId: $this->publishSiteId,
            status: $this->publishStatus,
            scheduleAtIso: $iso,
            publicationId: $pub->id
        ))->onQueue('publish');

        $platform = $provider === 'shopify' ? 'Shopify' : 'WordPress';
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
            'socialTarget' => 'required|in:facebook,instagram',
            'socialScheduleAt' => 'nullable|date',
        ]);

        if ($this->socialTarget === 'instagram' && $this->selectedImageAssetId <= 0) {
            session()->flash('success', 'Instagram kräver en vald bild från bildbanken.');
            return;
        }

        $now = Carbon::now();
        $scheduledAt = $this->socialScheduleAt ? Carbon::parse($this->socialScheduleAt) : null;

        $payload = [
            'text' => $this->extractPlainText((string) ($this->content->body_md ?? '')),
        ];

        if ($this->selectedImageAssetId > 0) {
            $payload['image_asset_id'] = (int)$this->selectedImageAssetId;
        }

        $pub = ContentPublication::create([
            'ai_content_id' => $this->content->id,
            'target'        => $this->socialTarget,
            'status'        => 'queued',
            'scheduled_at'  => $scheduledAt,
            'message'       => null,
            'payload'       => $payload,
        ]);

        if (!$scheduledAt || $scheduledAt->lte($now)) {
            if ($this->socialTarget === 'facebook') {
                dispatch(new PublishToFacebookJob($pub->id))->afterCommit()->onQueue('social');
            } else {
                dispatch(new PublishToInstagramJob($pub->id))->afterCommit()->onQueue('social');
            }
            session()->flash('success', ucfirst($this->socialTarget).' publicering startad.');
        } else {
            $delay = $scheduledAt->diffInSeconds($now);
            if ($this->socialTarget === 'facebook') {
                dispatch(new PublishToFacebookJob($pub->id))->delay($delay)->afterCommit()->onQueue('social');
            } else {
                dispatch(new PublishToInstagramJob($pub->id))->delay($delay)->afterCommit()->onQueue('social');
            }
            session()->flash('success', ucfirst($this->socialTarget)." schemalagd till {$scheduledAt->format('Y-m-d H:i')}.");
        }
    }

    public function publishSocialNow(): void
    {
        Gate::authorize('update', $this->content);

        $this->validate([
            'socialTarget' => 'required|in:facebook,instagram',
        ]);

        if ($this->socialTarget === 'instagram' && $this->selectedImageAssetId <= 0) {
            session()->flash('success', 'Instagram kräver en vald bild från bildbanken.');
            return;
        }

        $payload = [
            'text' => $this->extractPlainText((string) ($this->content->body_md ?? '')),
        ];

        if ($this->selectedImageAssetId > 0) {
            $payload['image_asset_id'] = (int)$this->selectedImageAssetId;
        }

        $pub = ContentPublication::create([
            'ai_content_id' => $this->content->id,
            'target'        => $this->socialTarget,
            'status'        => 'queued',
            'scheduled_at'  => null,
            'message'       => null,
            'payload'       => $payload,
        ]);

        if ($this->socialTarget === 'facebook') {
            dispatch(new PublishToFacebookJob($pub->id))->afterCommit()->onQueue('social');
        } else {
            dispatch(new PublishToInstagramJob($pub->id))->afterCommit()->onQueue('social');
        }

        session()->flash('success', ucfirst($this->socialTarget) . ' publicering startad.');
    }

    public function queueLinkedInQuick(): void
    {
        Gate::authorize('update', $this->content);

        $scheduledAt = $this->liQuickScheduleAt ? Carbon::parse($this->liQuickScheduleAt) : null;
        $now = Carbon::now();

        $payload = [
            'text' => $this->liQuickText ? $this->extractPlainText($this->liQuickText) : null,
        ];

        if ($this->selectedImageAssetId > 0) {
            $payload['image_asset_id'] = (int)$this->selectedImageAssetId;
        }

        $pub = ContentPublication::create([
            'ai_content_id' => $this->content->id,
            'target'        => 'linkedin',
            'status'        => 'queued',
            'scheduled_at'  => $this->liQuickScheduleAt ? Carbon::parse($this->liQuickScheduleAt) : null,
            'message'       => null,
            'payload'       => $payload,
        ]);

        if (!$scheduledAt || $scheduledAt->lte($now)) {
            $pub->update(['status' => 'processing']);
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
        $html = $md !== '' ? \Illuminate\Support\Str::of($md)->markdown() : '';

        $currentProvider = null;
        try {
            if ($this->publishSiteId) {
                $currentProvider = app(IntegrationManager::class)->forSite((int)$this->publishSiteId)->provider();
            }
        } catch (\Throwable) {
            $currentProvider = null;
        }

        $customer = app(CurrentCustomer::class)->get();
        $limit = null;
        try { $limit = optional(optional($customer)->plan)->publication_quota; } catch (\Throwable) { $limit = null; }

        $used = 0;
        if ($limit !== null && $customer) {
            $start = Carbon::now()->startOfMonth();
            $end   = Carbon::now()->endOfMonth();
            $used = ContentPublication::whereBetween('created_at', [$start, $end])
                ->whereHas('aiContent', fn($q) => $q->where('customer_id', $customer->id))
                ->count();
        }

        $this->publishQuotaLimit = $limit !== null ? (int)$limit : null;
        $this->publishQuotaUsed  = $used;
        $this->publishQuotaReached = $limit !== null ? ($used >= (int)$limit) : false;

        $isLocked = $this->isLocked();

        return view('livewire.a-i.detail', [
            'sites'               => $this->sites,
            'md'                  => $md,
            'html'                => $html,
            'currentProvider'     => $currentProvider,
            'publishQuotaReached' => $this->publishQuotaReached,
            'publishQuotaLimit'   => $this->publishQuotaLimit,
            'publishQuotaUsed'    => $this->publishQuotaUsed,
            'isLocked'            => $isLocked,
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
                $minIndent = 0; break;
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

    public function regenerate()
    {
        if ($this->content->status !== 'ready') {
            $this->addError('general', 'Kan bara generera om färdigt innehåll.');
            return;
        }

        $isPublished = $this->content->publications()->where('status', 'published')->exists();
        if ($isPublished) {
            session()->flash('error', 'Kan inte generera om innehåll som redan publicerats.');
            return;
        }

        $this->content->update([
            'status' => 'queued',
            'body_md' => null,
            'error' => null,
        ]);

        dispatch(new GenerateContentJob($this->content->id))->onQueue('ai');

        session()->flash('success', 'Genererar nytt innehåll...');
    }

    public function lockAfterPublication()
    {
        $this->content->update(['is_locked' => true]);
    }

    public function openGenModal(\App\Services\Billing\PlanService $plans, \App\Support\Usage $usage): void
    {
        $this->genError = '';
        $customer = app(\App\Support\CurrentCustomer::class)->get();
        if (!$customer) { $this->genError = 'Ingen kund hittades.'; $this->genShow = true; return; }

        $quota = $plans->getQuota($customer, 'ai.images.quota_month');
        $used  = $usage->countThisMonth((int)$customer->id, 'ai.images');

        if ($quota === 0) {
            $this->genError = 'Din plan tillåter inte bildgenerering.';
        } elseif (!is_null($quota) && $used >= $quota) {
            $this->genError = 'Månadskvoten för bildgenerering är slut.';
        }

        $this->genRemaining = is_null($quota) ? null : max(0, $quota - $used);
        $this->genAfter     = is_null($quota) ? null : max(0, $quota - $used - 1);
        $this->genShow = true;
    }

// Bekräfta och köa jobb
    public function confirmGenerateImageForContent(
        \App\Support\CurrentCustomer $current,
        \App\Services\Billing\PlanService $plans,
        \App\Support\Usage $usage
    ): void {
        $this->validate([
            'genFormat' => 'required|in:square,portrait,landscape',
        ]);

        $customer = $current->get();
        $site     = $current->getSite();
        if (!$customer || !$site) {
            $this->genError = 'Ingen aktiv kund/sajt vald.';
            return;
        }

        // Kvotkontroll igen precis innan kö
        $quota = $plans->getQuota($customer, 'ai.images.quota_month');
        $used  = $usage->countThisMonth((int)$customer->id, 'ai.images');
        if ($quota === 0) { $this->genError = 'Din plan tillåter inte bildgenerering.'; return; }
        if (!is_null($quota) && $used >= $quota) { $this->genError = 'Månadskvoten är slut.'; return; }

        // Bygg enkel prompt från texten
        $title = (string) ($this->content->title ?? 'Inlägg');
        $md    = (string) ($this->content->body_md ?? '');
        $summary = \Illuminate\Support\Str::limit(strip_tags($md), 600);

        $prompt = trim("
Skapa en professionell social bild som stödjer följande text (rubrik och sammanfattning):

Rubrik: {$title}
Sammanfattning: {$summary}

Krav:
- Ren, säljbar och varumärkesmässig komposition.
- Ingen inbäddad text i bilden (systemet lägger overlay själv vid behov).
- Hög kontrast mellan motiv och bakgrund. Undvik vattenstämplar och artefakter.
");

        // Mappa format → plattform/storlek
        $platform = match ($this->genFormat) {
            'portrait'  => 'facebook_story',   // 1080x1920
            'landscape' => 'blog',             // 1200x628
            default     => 'facebook_square',  // 1080x1080
        };

        // Köa jobb
        $this->genBusy = true;
        $this->genQueued = true;

        dispatch(new \App\Jobs\GenerateAiImageJob(
            customerId: (int) $customer->id,
            siteId: (int) $site->id,
            prompt: $prompt,
            platform: $platform
        ))->afterCommit()->onQueue('ai');

        // Stäng modal och trigga polling i UI
        $this->genShow = false;
        $this->dispatch('ai-image-queued');

        // Visa uppdaterad prognos i UI
        $this->genRemaining = is_null($quota) ? null : max(0, $quota - $used - 1);
        $this->genAfter = is_null($quota) ? null : max(0, $quota - $used - 1);

        $this->genBusy = false;
    }

    public function loadMediaLibrary(): void
    {
        // $this->dispatch('media-picker:refresh');
        // Lämnas tom så länge – bara för att undvika fel i JS-anropet.
    }
}
