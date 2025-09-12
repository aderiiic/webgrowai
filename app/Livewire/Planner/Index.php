<?php

namespace App\Livewire\Planner;

use App\Jobs\RefreshPublicationMetricsJob;
use App\Models\AiContent;
use App\Models\ContentPublication;
use App\Support\CurrentCustomer;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Carbon;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;

#[Layout('layouts.app')]
class Index extends Component
{
    #[Url] public ?int $siteId = null;
    #[Url] public string $channel = 'all';
    #[Url] public string $status = 'upcoming';
    #[Url] public ?int $open = null;
    #[Url] public ?int $content_id = null;

    // Vy-läge + vecka
    #[Url] public string $view = 'timeline'; // timeline|calendar
    #[Url] public ?string $from = null; // YYYY-MM-DD (måndag)

    public array $items = [];
    public ?array $selected = null;
    public ?string $rescheduleAt = null;

    // Snabbplanering
    public ?string $quickScheduleAt = null; // 'Y-m-d\TH:i'
    public string $quickTarget = 'facebook'; // wp|shopify|facebook|instagram|linkedin
    public ?int $quickContentId = null;

    public ?int $quickImageId = 0;

    // Till vy
    public array $readyContents = []; // [{id,title,site}]

    public function mount(CurrentCustomer $current): void
    {
        if ($this->siteId === null) {
            $this->siteId = $current->getSiteId();
        }
        if (!$this->from) {
            $this->from = Carbon::now()->startOfWeek(Carbon::MONDAY)->toDateString();
        }
    }

    #[On('media-selected')]
    public function onMediaSelected(int $id): void
    {
        $this->quickImageId = $id;
        session()->flash('success', 'Bild vald – klicka "Använd bild" för att koppla till den valda publiceringen.');
    }

    public function applyImageToSelected(CurrentCustomer $current): void
    {
        if (!$this->selected || empty($this->selected['id'])) {
            $this->dispatch('notify', type: 'warning', message: 'Ingen publicering vald.');
            return;
        }
        if (!$this->quickImageId) {
            $this->dispatch('notify', type: 'warning', message: 'Välj en bild först.');
            return;
        }

        $customer = $current->get();
        abort_unless($customer, 403);

        $pub = ContentPublication::with('content:id,customer_id,site_id')->findOrFail((int)$this->selected['id']);
        abort_unless($pub->content && (int)$pub->content->customer_id === (int)$customer->id, 403);

        // Begränsa mot aktiv site (om satt)
        $activeSiteId = (int) ($current->getSiteId() ?: 0);
        if ($activeSiteId > 0) {
            abort_unless((int)$pub->content->site_id === $activeSiteId, 403);
        }

        // Sätt bild i payload
        $payload = $pub->payload ?? [];
        $payload['image_asset_id'] = (int)$this->quickImageId;

        $pub->update([
            'payload' => $payload,
            'message' => 'Bild kopplad till publicering',
        ]);

        // Uppdatera selected + items i minnet
        $this->selected['message'] = 'Bild kopplad till publicering';
        foreach ($this->items as &$row) {
            if ((int)$row['id'] === (int)$pub->id) {
                $row['message'] = $this->selected['message'];
                break;
            }
        }
        unset($row);

        session()->flash('success', 'Bilden kopplades till publiceringen.');
    }

    public function removeImageFromSelected(CurrentCustomer $current): void
    {
        if (!$this->selected || empty($this->selected['id'])) {
            $this->dispatch('notify', type: 'warning', message: 'Ingen publicering vald.');
            return;
        }

        $customer = $current->get();
        abort_unless($customer, 403);

        $pub = ContentPublication::with('content:id,customer_id,site_id')->findOrFail((int)$this->selected['id']);
        abort_unless($pub->content && (int)$pub->content->customer_id === (int)$customer->id, 403);

        $activeSiteId = (int) ($current->getSiteId() ?: 0);
        if ($activeSiteId > 0) {
            abort_unless((int)$pub->content->site_id === $activeSiteId, 403);
        }

        $payload = $pub->payload ?? [];
        unset($payload['image_asset_id']);

        $pub->update([
            'payload' => $payload,
            'message' => 'Bild borttagen från publicering',
        ]);

        $this->selected['message'] = 'Bild borttagen från publicering';
        foreach ($this->items as &$row) {
            if ((int)$row['id'] === (int)$pub->id) {
                $row['message'] = $this->selected['message'];
                break;
            }
        }
        unset($row);

        session()->flash('success', 'Bilden togs bort från publiceringen.');
    }

    public function select(int $publicationId): void
    {
        foreach ($this->items as $row) {
            if ((int)($row['id'] ?? 0) === $publicationId) {
                $this->selected = $row;
                $this->rescheduleAt = $row['scheduled_at']
                    ? Carbon::parse($row['scheduled_at'])->format('Y-m-d\TH:i')
                    : null;
                // Förifyll snabbplaneringens datum med det valda inläggets dag kl 09:00
                $base = $row['scheduled_at']
                    ? Carbon::parse($row['scheduled_at'])->startOfHour()
                    : Carbon::now()->startOfHour();
                $this->quickScheduleAt = $base->format('Y-m-d\TH:i');
                return;
            }
        }
        $this->selected = null;
        $this->rescheduleAt = null;
    }

    public function clearSelection(): void
    {
        $this->selected = null;
        $this->rescheduleAt = null;
        $this->quickScheduleAt = null;
        $this->quickTarget = 'facebook';
        $this->quickContentId = null;
    }

    public function setView(string $mode): void
    {
        $this->view = in_array($mode, ['timeline','calendar'], true) ? $mode : 'timeline';
    }

    public function prevWeek(): void
    {
        $start = Carbon::parse($this->from)->startOfWeek(Carbon::MONDAY)->subWeek();
        $this->from = $start->toDateString();
    }

    public function nextWeek(): void
    {
        $start = Carbon::parse($this->from)->startOfWeek(Carbon::MONDAY)->addWeek();
        $this->from = $start->toDateString();
    }

    public function today(): void
    {
        $this->from = Carbon::now()->startOfWeek(Carbon::MONDAY)->toDateString();
    }

    // Öppna snabbplanering för vald dag (sätter default 09:00 den dagen)
    public function startQuickPlan(string $dateYmd): void
    {
        try {
            $dt = Carbon::parse($dateYmd)->setTime(9, 0, 0);
            $this->quickScheduleAt = $dt->format('Y-m-d\TH:i');
        } catch (\Throwable) {
            $this->quickScheduleAt = null;
        }
        // För panel och snabb åtkomst
        if (!$this->selected && !empty($this->items)) {
            $this->selected = $this->items[0];
        }
    }

    public function cancelPublication(int $publicationId, CurrentCustomer $current): void
    {
        $customer = $current->get();
        abort_unless($customer, 403);

        $pub = ContentPublication::with('content:id,customer_id,site_id')->findOrFail($publicationId);
        abort_unless($pub->content && (int)$pub->content->customer_id === (int)$customer->id, 403);

        $activeSiteId = (int) ($current->getSiteId() ?: 0);
        if ($activeSiteId > 0) {
            abort_unless((int)$pub->content->site_id === $activeSiteId, 403);
        }

        if (in_array($pub->status, ['published', 'failed', 'cancelled'], true)) {
            $this->dispatch('notify', type: 'warning', message: 'Kan inte avbryta – status: ' . $pub->status);
            return;
        }

        $pub->update([
            'status'       => 'cancelled',
            'cancelled_at' => now(),
            'message'      => 'Avbruten av användare',
        ]);

        foreach ($this->items as &$row) {
            if ($row['id'] === (int)$publicationId) {
                $row['status'] = 'cancelled';
                $row['message'] = 'Avbruten av användare';
                break;
            }
        }
        unset($row);

        if ($this->selected && (int)$this->selected['id'] === (int)$publicationId) {
            $this->selected['status'] = 'cancelled';
            $this->selected['message'] = 'Avbruten av användare';
        }

        session()->flash('success', 'Publiceringen avbruten.');
    }

    public function reschedulePublication(int $publicationId, CurrentCustomer $current): void
    {
        $this->validate([
            'rescheduleAt' => 'required|date',
        ], [], [
            'rescheduleAt' => 'Ny tid',
        ]);

        $customer = $current->get();
        abort_unless($customer, 403);

        $pub = ContentPublication::with(['content:id,customer_id,site_id'])->findOrFail($publicationId);
        abort_unless($pub->content && (int)$pub->content->customer_id === (int)$customer->id, 403);

        $activeSiteId = (int) ($current->getSiteId() ?: 0);
        if ($activeSiteId > 0) {
            abort_unless((int)$pub->content->site_id === $activeSiteId, 403);
        }

        if (in_array($pub->status, ['published', 'failed', 'cancelled'], true)) {
            $this->dispatch('notify', type: 'warning', message: 'Kan inte ändra tid – status: ' . $pub->status);
            return;
        }

        $newAt = Carbon::parse($this->rescheduleAt);
        $payload = $pub->payload ?? [];
        $payload['date'] = $newAt->toIso8601String();
        $newStatus = $newAt ? 'scheduled' : 'queued';

        $pub->update([
            'scheduled_at' => $newAt,
            'status'       => $newStatus,
            'payload'      => $payload,
            'message'      => 'Tid uppdaterad till ' . $newAt->format('Y-m-d H:i'),
        ]);

        foreach ($this->items as &$row) {
            if ($row['id'] === (int)$publicationId) {
                $row['scheduled_at'] = $newAt->toDateTimeString();
                $row['status'] = $newStatus;
                $row['message'] = 'Tid uppdaterad till ' . $newAt->format('Y-m-d H:i');
                break;
            }
        }
        unset($row);

        if ($this->selected && (int)$this->selected['id'] === (int)$publicationId) {
            $this->selected['scheduled_at'] = $newAt->toDateTimeString();
            $this->selected['status'] = $newStatus;
            $this->selected['message'] = 'Tid uppdaterad till ' . $newAt->format('Y-m-d H:i');
        }

        session()->flash('success', 'Publiceringen har fått ny tid.');
    }

    // Skapa ny schemalagd publicering snabbt
    public function createQuickPublication(CurrentCustomer $current): void
    {
        $this->validate([
            'quickContentId'   => 'required|integer|exists:ai_contents,id',
            'quickTarget'      => 'required|in:wp,shopify,facebook,instagram,linkedin',
            'quickScheduleAt'  => 'required|date',
        ], [], [
            'quickContentId'  => 'Innehåll',
            'quickTarget'     => 'Kanal',
            'quickScheduleAt' => 'Tid',
        ]);

        $customer = $current->get();
        abort_unless($customer, 403);

        $content = AiContent::query()
            ->where('id', (int)$this->quickContentId)
            ->where('customer_id', $customer->id)
            ->firstOrFail();

        // Begränsa ev. efter vald sajt
        if ($this->siteId && (int)$content->site_id !== (int)$this->siteId) {
            $this->addError('quickContentId', 'Valt innehåll tillhör inte den valda sajten.');
            return;
        }

        $scheduledAt = Carbon::parse($this->quickScheduleAt);

        $payload = [];
        if (in_array($this->quickTarget, ['wp', 'shopify'], true)) {
            // Webb – bädda in site_id + status (låt downstream jobb hantera)
            $payload['site_id'] = $content->site_id;
            $payload['status']  = 'future';
            $payload['date']    = $scheduledAt->toIso8601String();
        } else {
            // Social – text skapas i jobben utifrån content/body_md. Bild hanteras separat.
            $payload['text'] = null;
        }

        if ($this->quickImageId > 0) {
            $payload['image_asset_id'] = (int)$this->quickImageId;
        }

        $pub = ContentPublication::create([
            'ai_content_id' => $content->id,
            'target'        => $this->quickTarget,
            'status'        => 'scheduled',
            'scheduled_at'  => $scheduledAt,
            'message'       => 'Schemalagd via snabbplanering',
            'payload'       => $payload,
        ]);

        // Uppdatera listan i UI
        $this->items[] = [
            'id'            => (int)$pub->id,
            'ai_content_id' => (int)$content->id,
            'title'         => (string)($content->title ?? '(utan titel)'),
            'site'          => (string)($content->site?->name ?? ''),
            'target'        => (string)$pub->target,
            'status'        => (string)$pub->status,
            'scheduled_at'  => $scheduledAt->toDateTimeString(),
            'message'       => (string)$pub->message,
            'external_url'  => null,
        ];

        $this->selected = end($this->items) ?: $this->selected;
        $this->rescheduleAt = $scheduledAt->format('Y-m-d\TH:i');

        // Töm form (valfritt behålla kanal)
        // $this->quickTarget = 'facebook';
        $this->quickContentId = null;

        session()->flash('success', 'Publiceringen schemalagd.');
    }

    public function refreshMetrics(int $publicationId, CurrentCustomer $current): void
    {
        $customer = $current->get();
        abort_unless($customer, 403);

        $pub = ContentPublication::with('content:id,customer_id,site_id')->findOrFail($publicationId);
        abort_unless((int)$pub->content?->customer_id === (int)$customer->id, 403);

        // NYTT: säkerställ aktiv site-kontext om en site är vald
        $activeSiteId = (int) ($current->getSiteId() ?: 0);
        if ($activeSiteId > 0) {
            abort_unless((int)$pub->content->site_id === $activeSiteId, 403);
        }

        dispatch(new RefreshPublicationMetricsJob($pub->id))->onQueue('metrics')->afterCommit();
        session()->flash('success', 'Uppdatering av statistik har startat.');
    }

    public function render(CurrentCustomer $current): View
    {
        $customer = $current->get();
        abort_unless($customer, 403);

        $now = Carbon::now();
        $to = (clone $now)->addDays(30);

        $q = ContentPublication::query()
            ->with([
                'content:id,title,site_id,customer_id',
                'content.site:id,name',
            ])
            ->whereHas('content', fn($x) => $x->where('customer_id', $customer->id));

        if ($this->siteId) {
            $q->whereHas('content', fn($x) => $x->where('site_id', $this->siteId));
        }

        if ($this->channel !== 'all') {
            $map = [
                'wp'        => ['wp', 'wordpress'],
                'shopify'   => ['shopify'],
                'facebook'  => ['facebook'],
                'instagram' => ['instagram'],
                'linkedin'  => ['linkedin'],
            ];
            $targets = $map[$this->channel] ?? [$this->channel];
            $q->whereIn('target', $targets);
        }

        if ($this->status === 'upcoming') {
            $q->where(function ($w) use ($now, $to) {
                $w->whereBetween('scheduled_at', [$now, $to])
                    ->orWhere(function ($w2) {
                        $w2->whereIn('status', ['queued', 'scheduled', 'processing'])
                            ->whereNull('scheduled_at');
                    });
            })->whereIn('status', ['queued', 'scheduled', 'processing']);
        } elseif ($this->status !== 'all') {
            $q->where('status', $this->status);
        }

        if ($this->content_id) {
            $q->where('ai_content_id', (int)$this->content_id);
        }

        $rows = $q->orderByRaw('scheduled_at IS NULL, scheduled_at ASC')
            ->latest('id')
            ->limit(500)
            ->get();

        $this->items = $rows->map(function ($p) {
            $siteName  = optional($p->content?->site)->name;
            $title     = (string) ($p->content?->title ?? '(utan titel)');
            $scheduled = $p->scheduled_at ? Carbon::parse($p->scheduled_at)->toDateTimeString() : null;

            $target = $p->target;
            if ($target === 'wordpress') $target = 'wp';

            return [
                'id'            => (int)$p->id,
                'ai_content_id' => (int)$p->ai_content_id,
                'title'         => $title,
                'site'          => $siteName,
                'target'        => (string)$target,
                'status'        => (string)$p->status,
                'scheduled_at'  => $scheduled,
                'message'       => $p->message,
                'external_url'  => $p->external_url ?? null,
                'metrics'       => $p->metrics ?? null,
                'metrics_at'    => $p->metrics_refreshed_at?->toDateTimeString(),
            ];
        })->values()->all();

        if ($this->selected === null && $this->open) {
            $this->select((int)$this->open);
        } elseif ($this->selected === null && $this->content_id && !empty($this->items)) {
            $first = collect($this->items)->firstWhere('ai_content_id', (int)$this->content_id);
            if ($first) $this->selected = $first;
        }

        if ($this->selected === null && !empty($this->items)) {
            $this->selected = $this->items[0];
            $this->rescheduleAt = $this->selected['scheduled_at']
                ? Carbon::parse($this->selected['scheduled_at'])->format('Y-m-d\TH:i')
                : null;
        }

        $weekStart = Carbon::parse($this->from)->startOfWeek(Carbon::MONDAY);
        $weekDays = [];
        for ($i = 0; $i < 7; $i++) $weekDays[] = (clone $weekStart)->addDays($i);

        $aiQ = AiContent::query()
            ->select(['id','title','site_id'])
            ->where('customer_id', $customer->id)
            ->where('status', 'ready')
            ->latest('id')
            ->limit(50);

        if ($this->siteId) $aiQ->where('site_id', $this->siteId);

        $this->readyContents = $aiQ->get()->map(fn($c) => [
            'id'    => (int)$c->id,
            'title' => (string)($c->title ?: '(utan titel)'),
            'site'  => (int)$c->site_id,
        ])->toArray();

        return view('livewire.planner.index', [
            'items'        => $this->items,
            'selected'     => $this->selected,
            'now'          => $now,
            'to'           => $to,
            'weekDays'     => $weekDays,
            'weekStart'    => $weekStart,
            'weekEnd'      => (clone $weekStart)->addDays(6),
            'readyContents'=> $this->readyContents,
        ]);
    }

    public function reloadSelected(): void
    {
        if (!$this->selected || empty($this->selected['id'])) {
            return;
        }

        $p = \App\Models\ContentPublication::query()
            ->with([
                'content:id,title,site_id,customer_id',
                'content.site:id,name',
            ])
            ->find($this->selected['id']);

        if (!$p) {
            // Posten kan ha raderats – töm panelen säkert
            $this->selected = null;
            return;
        }

        $target = $p->target === 'wordpress' ? 'wp' : (string) $p->target;

        $updated = [
            'id'            => (int) $p->id,
            'ai_content_id' => (int) $p->ai_content_id,
            'title'         => (string) ($p->content?->title ?? '(utan titel)'),
            'site'          => (string) ($p->content?->site?->name ?? ''),
            'target'        => $target,
            'status'        => (string) $p->status,
            'scheduled_at'  => $p->scheduled_at ? $p->scheduled_at->toDateTimeString() : null,
            'message'       => $p->message,
            'external_url'  => $p->external_url ?? null,
            'metrics'       => $p->metrics ?? null,
            'metrics_at'    => $p->metrics_refreshed_at?->toDateTimeString(),
        ];

        // Uppdatera selected
        $this->selected = $updated;

        // Uppdatera motsvarande rad i items
        foreach ($this->items as &$row) {
            if ((int) ($row['id'] ?? 0) === (int) $p->id) {
                $row = $updated;
                break;
            }
        }
        unset($row);
    }
}
