<?php

namespace App\Livewire\AI;

use App\Jobs\PublishAiContentJob; // Byt till generiska jobbet
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

    // UI-hjälp: om kvot är nådd (beräknas i render)
    public bool $publishQuotaReached = false;
    public ?int $publishQuotaLimit = null;
    public int $publishQuotaUsed = 0;

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

        // Kvotkontroll för publiceringar (månad)
        // Om ingen plan/limit hittas: obegränsat
        $limit = null;
        try {
            $limit = optional(optional($customer)->plan)->publication_quota;
        } catch (\Throwable) {
            $limit = null;
        }

        if ($limit !== null) {
            $start = Carbon::now()->startOfMonth();
            $end   = Carbon::now()->endOfMonth();

            $used = ContentPublication::whereBetween('created_at', [$start, $end])
                ->whereHas('aiContent', function ($q) use ($customer) {
                    $q->when($customer, fn($q2) => $q2->where('customer_id', $customer->id));
                })
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

        // Hämta provider för vald sajt (styr target och feedback)
        $client = app(IntegrationManager::class)->forSite($this->publishSiteId);
        $provider = $client->provider(); // 'wordpress' | 'shopify' | 'custom'
        // Viktigt: spara target = provider för konsistens
        $target = $provider;

        $pub = ContentPublication::create([
            'ai_content_id' => $this->content->id,
            'target'        => $target,
            'status'        => 'queued',
            'scheduled_at'  => $iso ? Carbon::parse($this->publishAt) : null,
            'message'       => null,
            'payload'       => [
                'site_id' => $this->publishSiteId,
                'status'  => $this->publishStatus,
                // Låt datum vara ISO8601 – jobbet mappar till rätt fält (date/date_gmt/publication_time) per provider
                'date'    => $iso,
            ],
        ]);

        // Publicera via generiskt jobb (IntegrationManager hanterar Shopify/WordPress)
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

        $now = Carbon::now();
        $scheduledAt = $this->socialScheduleAt ? Carbon::parse($this->socialScheduleAt) : null;

        $pub = ContentPublication::create([
            'ai_content_id' => $this->content->id,
            'target'        => $this->socialTarget,
            'status'        => 'queued',
            'scheduled_at'  => $scheduledAt,
            'message'       => null,
            'payload'       => [
                'text'   => $this->extractPlainText((string) ($this->content->body_md ?? '')),
                // valfria bildnycklar kan sättas här om du vill gemensam hantering
            ],
        ]);

        // Direktpublicering (ingen tid eller dåtid): sätt processing direkt och dispatcha
        if (!$scheduledAt || $scheduledAt->lte($now)) {
            $pub->update(['status' => 'processing']);

            if ($this->socialTarget === 'facebook') {
                dispatch(new PublishToFacebookJob($pub->id))->onQueue('social');
            } else {
                dispatch(new PublishToInstagramJob($pub->id))->onQueue('social');
            }

            session()->flash('success', ucfirst($this->socialTarget).' publicering startad.');
        } else {
            // Schemalagt: social:process-scheduled tar den vid rätt tid
            session()->flash('success', ucfirst($this->socialTarget)." schemalagd till {$scheduledAt->format('Y-m-d H:i')}.");
        }
    }

    public function queueLinkedInQuick(): void
    {
        Gate::authorize('update', $this->content);

        // validera enkel input (text kan vara tom -> använd titel som fallback i jobbet)
        $scheduledAt = $this->liQuickScheduleAt ? Carbon::parse($this->liQuickScheduleAt) : null;
        $now = Carbon::now();

        $payload = [
            'text' => $this->liQuickText ? $this->extractPlainText($this->liQuickText) : null,
        ];
        if ($this->liQuickImagePrompt) {
            $payload['image'] = ['generate' => true, 'prompt' => $this->liQuickImagePrompt];
        }

        $pub = ContentPublication::create([
            'ai_content_id' => $this->content->id,
            'target'        => 'linkedin',
            'status'        => 'queued',
            'scheduled_at'  => $scheduledAt,
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
        $html = $md !== '' ? Str::of($md)->markdown() : '';

        // Ta reda på provider för nuvarande vald sajt så vi kan visa rätt paneltext/ikon
        $currentProvider = null;
        try {
            if ($this->publishSiteId) {
                $currentProvider = app(IntegrationManager::class)->forSite((int)$this->publishSiteId)->provider(); // 'shopify' | 'wordpress' | null
            }
        } catch (\Throwable) {
            $currentProvider = null;
        }

        // Beräkna kvotläge för UI
        $customer = app(CurrentCustomer::class)->get();
        $limit = null;
        try {
            $limit = optional(optional($customer)->plan)->publication_quota;
        } catch (\Throwable) {
            $limit = null;
        }

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

        return view('livewire.a-i.detail', [
            'sites'                 => $this->sites,
            'md'                    => $md,
            'html'                  => $html,
            'currentProvider'       => $currentProvider, // 'shopify' | 'wordpress' | null
            'publishQuotaReached'   => $this->publishQuotaReached,
            'publishQuotaLimit'     => $this->publishQuotaLimit,
            'publishQuotaUsed'      => $this->publishQuotaUsed,
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
