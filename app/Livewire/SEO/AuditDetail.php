<?php

namespace App\Livewire\SEO;

use App\Models\SeoAudit;
use App\Support\CurrentCustomer;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class AuditDetail extends Component
{
    public SeoAudit $audit;
    public string $filter = 'all'; // all|title|meta|lighthouse|content|performance|links
    public ?string $q = null;      // enkel sökning i findings
    public bool $showPlainHelp = true; // visa enklare förklaring

    public function mount(int $auditId, CurrentCustomer $current): void
    {
        $this->audit = SeoAudit::with('items', 'site')->findOrFail($auditId);

        $customer = $current->get();
        abort_unless($customer && $customer->sites()->whereKey($this->audit->site_id)->exists(), 403);
    }

    public function clearSearch(): void
    {
        $this->q = null;
    }

    public function render()
    {
        // 1) Fyndlistan: respektera valt filter (eller visa alla)
        $items = $this->audit->items()
            ->when($this->filter !== 'all', fn($q) => $q->where('type', $this->filter))
            ->when(!empty($this->q), function ($q) {
                $term = '%'.trim((string) $this->q).'%';
                $q->where(function ($sub) use ($term) {
                    $sub->where('title', 'like', $term)
                        ->orWhere('message', 'like', $term)
                        ->orWhere('data', 'like', $term);
                });
            })
            ->latest()
            ->paginate(20);

        // 2) Yoast-status: övergripande för hela audit
        $yoastDetected = $this->audit->items->contains(function ($i) {
            $raw = $i->data ?? null;
            if (is_string($raw) && $raw !== '') {
                try { $decoded = json_decode($raw, true, 512, JSON_THROW_ON_ERROR); $data = is_array($decoded) ? $decoded : []; }
                catch (\Throwable) { $data = []; }
            } elseif (is_array($raw)) {
                $data = $raw;
            } else {
                $data = [];
            }
            return array_key_exists('yoast', $data)
                || array_key_exists('yoast_title', $data)
                || array_key_exists('yoast_description', $data);
        });

        // 3) Sammanfattning
        $summary = $this->makePlainSummary();

        // 4) Lighthouse-detaljer:
        //    - När filter = 'lighthouse': visa endast lighthouse-items (respekt. sök)
        //    - När filter = 'all': visa lighthouse från hela auditen (respekt. sök)
        //    - Annars: visa inga lighthouse-detaljer (hanteras i vyn)
        $lighthouse = [
            'performance' => [],
            'accessibility' => [],
            'best-practices' => [],
            'seo' => [],
        ];

        $lighthouseBase = $this->audit->items()
            ->when($this->filter === 'lighthouse', fn($q) => $q->where('type', 'lighthouse'))
            ->when($this->filter === 'all', fn($q) => $q->where('type', 'lighthouse'))
            // Övriga filter: returnera tom samling genom ett "false where" som matchar noll rader
            ->when(!in_array($this->filter, ['lighthouse', 'all'], true), fn($q) => $q->whereRaw('1=0'))
            ->when(!empty($this->q), function ($q) {
                $term = '%'.trim((string) $this->q).'%';
                $q->where(function ($sub) use ($term) {
                    $sub->where('title', 'like', $term)
                        ->orWhere('message', 'like', $term)
                        ->orWhere('data', 'like', $term);
                });
            })
            ->latest()
            ->get();

        foreach ($lighthouseBase as $it) {
            $data = is_array($it->data) ? $it->data : (is_string($it->data) ? (json_decode($it->data, true) ?: []) : []);
            if (!is_array($data)) continue;
            $cat = $data['lh_category'] ?? null;
            if (is_string($cat) && isset($lighthouse[$cat])) {
                $lighthouse[$cat][] = [
                    'title' => $it->title,
                    'message' => $it->message,
                    'impact' => $data['lh_impact'] ?? null,
                    'opportunity' => $data['lh_opportunity'] ?? null,
                    'url' => $data['url'] ?? null,
                ];
            }
        }

        return view('livewire.seo.audit-detail', [
            'audit'         => $this->audit,
            'items'         => $items,
            'yoastDetected' => $yoastDetected,
            'summary'       => $summary,
            'lighthouse'    => $lighthouse,
            'filter'        => $this->filter,
        ]);
    }

    private function makePlainSummary(): array
    {
        $counts = [
            'title'       => 0,
            'meta'        => 0,
            'content'     => 0,
            'performance' => 0,
            'lighthouse'  => 0,
            'links'       => 0,
        ];

        foreach ($this->audit->items as $i) {
            $t = (string) ($i->type ?? 'other');
            if (isset($counts[$t])) $counts[$t]++;
        }

        $bullets = [];
        if ($counts['title'] > 0) {
            $bullets[] = 'Finslipa sidtitlarna: korta ner till 50–60 tecken och börja med viktigaste sökordet.';
        }
        if ($counts['meta'] > 0) {
            $bullets[] = 'Skriv metabeskrivningar på 140–160 tecken som lockar till klick.';
        }
        if ($counts['content'] > 0) {
            $bullets[] = 'Förbättra innehåll: besvara frågor och använd tydliga underrubriker.';
        }
        if ($counts['performance'] > 0 || $counts['lighthouse'] > 0) {
            $bullets[] = 'Hastighet: komprimera bilder och undvik tunga script.';
        }
        if ($counts['links'] > 0) {
            $bullets[] = 'Stärk interna länkar till relevanta sidor.';
        }

        if (empty($bullets)) {
            $bullets[] = 'Allt ser bra ut! Håll sidorna uppdaterade och följ upp trafiken.';
        }

        return [
            'headline' => 'Snabb sammanfattning',
            'tips'     => $bullets,
        ];
    }
}
