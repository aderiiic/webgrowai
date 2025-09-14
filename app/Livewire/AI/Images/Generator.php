<?php

namespace App\Livewire\AI\Images;

use App\Jobs\GenerateAiImageJob;
use App\Models\ImageAsset;
use App\Models\Site;
use App\Support\CurrentCustomer;
use App\Support\Usage;
use App\Services\Billing\PlanService;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;

#[Layout('layouts.app')]
class Generator extends Component
{
    public ?int $site_id = null;

    // Formfält
    public string $image_type = 'Produktbild';
    public string $background = 'vit';
    public string $style = '';
    public string $title = '';
    public bool $overlay_enabled = false;
    public string $overlay_text = '';
    public string $platform = 'facebook_square';

    // UI state
    public bool $busy = false;
    public bool $queued = false;
    public string $error = '';
    public string $finalPrompt = '';
    public array $items = []; // [{id, name}]
    private int $lastCount = 0; // för auto-hide av köbanner

    // Preview-modal
    public bool $showPreview = false;
    public ?int $previewId = null;
    public string $previewName = '';
    public string $previewUrl = '';

    // Kvot/plan
    public ?int $quota = null;
    public int $used = 0;
    public ?int $remaining = null;
    public bool $aiEnabled = true;

    public function mount(CurrentCustomer $current, PlanService $plans, Usage $usage): void
    {
        $this->site_id = $current->getSiteId() ?? $current->get()?->sites()->orderBy('name')->value('id');

        $customer = $current->get();
        if ($customer) {
            $this->quota     = $plans->getQuota($customer, 'ai.images.quota_month');
            $this->used      = $usage->countThisMonth((int) $customer->id, 'ai.images');
            $this->remaining = is_null($this->quota) ? null : max(0, $this->quota - $this->used);
            $this->aiEnabled = is_null($this->quota) ? true : ($this->quota > 0);
        }

        $this->loadList();
        $this->lastCount = count($this->items);
    }

    #[On('active-site-updated')]
    public function onActiveSiteUpdated(?int $siteId): void
    {
        $this->site_id = $siteId;
        $this->loadList();
        $this->lastCount = count($this->items);
    }

    public function submit(CurrentCustomer $current, PlanService $plans, Usage $usage): void
    {
        $this->validate([
            'image_type'      => 'required|string',
            'background'      => 'required|string',
            'style'           => 'nullable|string',
            'title'           => 'nullable|string|max:180',
            'overlay_enabled' => 'boolean',
            'overlay_text'    => 'nullable|string|max:200',
            'platform'        => 'required|string',
        ]);

        $this->error = '';
        $this->finalPrompt = '';

        $customer = $current->get();
        $site = $this->site_id ? Site::find($this->site_id) : null;

        if (!$customer || !$site) {
            $this->error = 'Ingen aktiv kund/sajt vald.';
            return;
        }

        $quota = $plans->getQuota($customer, 'ai.images.quota_month');
        $used  = $usage->countThisMonth((int) $customer->id, 'ai.images');

        if ($quota === 0) {
            $this->error = 'Din plan tillåter inte bildgenerering.';
            return;
        }
        if (!is_null($quota) && $used >= $quota) {
            $this->error = 'Månadskvoten för bildgenerering är slut.';
            return;
        }

        // Bygg prompt
        $this->finalPrompt = $this->buildPrompt($site);

        // Queue:a jobbet
        $this->busy = true;
        $this->queued = true;
        dispatch(new GenerateAiImageJob(
            customerId: (int) $customer->id,
            siteId: $site->id,
            prompt: $this->finalPrompt,
            platform: $this->platform
        ))->afterCommit()->onQueue('ai');

        // UI-feedback
        $this->used = $used + 1;
        $this->remaining = is_null($quota) ? null : max(0, $quota - $this->used);
        $this->aiEnabled = is_null($quota) ? true : ($quota > 0);

        // Polla listan en stund
        $this->dispatch('ai-image-queued');

        $this->busy = false;
    }

    public function loadList(): void
    {
        $this->items = [];
        $customer = app(CurrentCustomer::class)->get();
        if (!$customer) return;

        $assets = ImageAsset::query()
            ->where('customer_id', (int) $customer->id)
            ->orderByDesc('created_at')
            ->take(48)
            ->get(['id', 'original_name']);

        $this->items = $assets->map(fn(ImageAsset $a) => [
            'id'   => $a->id,
            'name' => $a->original_name ?: ('Bild #' . $a->id),
        ])->all();

        // Auto-hide köbanner om det kommit nya bilder
        $newCount = count($this->items);
        if ($this->queued && $newCount > $this->lastCount) {
            $this->queued = false;
        }
        $this->lastCount = $newCount;
    }

    public function openPreview(int $id): void
    {
        $asset = ImageAsset::find($id);
        if (!$asset) return;

        $this->previewId = $asset->id;
        $this->previewName = $asset->original_name ?: ('Bild #' . $asset->id);

        try {
            // Privat S3 – generera temporär URL om möjligt
            $this->previewUrl = Storage::disk($asset->disk ?? 's3')->temporaryUrl($asset->path, now()->addMinutes(10));
        } catch (\Throwable) {
            // Fallback: försök vanlig URL (om publik)
            $this->previewUrl = Storage::disk($asset->disk ?? 's3')->url($asset->path);
        }

        $this->showPreview = true;
    }

    public function closePreview(): void
    {
        $this->showPreview = false;
        $this->previewId = null;
        $this->previewName = '';
        $this->previewUrl = '';
    }

    public function render(): View
    {
        return view('livewire.a-i.images.generator');
    }

    private function buildPrompt(Site $site): string
    {
        $motifMap = [
            'Produktbild'    => 'Högupplöst produktfoto i fokus, realistisk ljussättning, subtila skuggor, säljinriktad presentation',
            'Facebook-bild'  => 'Social-first komposition som stannar i flödet, tydlig huvudpunkt och bra läsbarhet',
            'LinkedIn-bild'  => 'Professionell och förtroendeingivande bild med ren layout och balanserade färger',
            'Bloggbild'      => 'Illustrativ header-bild som stödjer ämnet, bra kontrast och modern estetik',
            'Instagrambild'  => 'Visuellt stark och estetisk med trendig look, fungerar i kvadrat/porträtt',
        ];
        $bgMap = [
            'vit' => 'ren vit bakgrund, studiosetting',
            'annan färg' => 'slät färgad bakgrund som harmoniserar med varumärket',
            'natur' => 'naturlig miljö, mjukt ljus, äkta känsla',
            'anpassad till titel' => 'miljö och färgpalett anpassad till titeln/temat',
        ];
        $styleHints = [
            'icy' => 'kylig, krispig ton, svagt blåskimmer, hög klarhet',
            'blur' => 'svag bakgrundsblur för djupkänsla',
            'clean' => 'minimalistisk, ren, luftigt negativt utrymme',
            'minimal' => 'få element, elegant, fokus på motivet',
            'branding-anpassad' => 'färger och bildspråk som matchar varumärkesrösten',
        ];

        $motif = $motifMap[$this->image_type] ?? $this->image_type;
        $bg    = $bgMap[$this->background] ?? $this->background;
        $styleParts = [];
        if ($this->style !== '') {
            foreach (explode(',', $this->style) as $s) {
                $s = trim($s);
                $styleParts[] = $styleHints[$s] ?? $s;
            }
        }
        $overlay = $this->overlay_enabled && $this->overlay_text !== ''
            ? "Lägg in en diskret text-overlay med följande text: “{$this->overlay_text}”. Se till att texten har hög läsbarhet (tillräcklig kontrast), placering bort från viktiga bildfokus och att layouten fungerar för beskärning."
            : "Ingen text-overlay.";
        $title = $this->title !== '' ? "Titel/tema: {$this->title}." : "Ingen explicit titel; håll motivet tydligt och relevant.";

        $contextDetails = "Ta hänsyn till sajtens kontext: {$site->aiContextSummary()}. Varumärkesröst: " . ($site->effectiveBrandVoice() ?: '-') .
            ". Målgrupp: " . ($site->effectiveAudience() ?: '-') .
            ". Mål: " . ($site->effectiveGoal() ?: '-') .
            ". Nyckelord: " . implode(', ', $site->effectiveKeywords() ?: []);

        $productExtras = (stripos($this->image_type, 'produkt') !== false)
            ? "Om en dryck eller flaska förekommer: realistiska vattendroppar på ytan, ser fräsch och läskande ut."
            : "";

        $pfText = match ($this->platform) {
            'facebook_square' => 'Facebook 1080x1080',
            'facebook_story'  => 'Facebook/Instagram Story 1080x1920',
            'instagram'       => 'Instagram 1080x1350',
            'linkedin'        => 'LinkedIn 1080x1080',
            'blog'            => 'Blogg 1200x628 (1.91:1)',
            default           => $this->platform,
        };

        return trim("
Skapa en bild med följande specifikationer:
- Motiv: {$motif}.
- Bakgrund: {$bg}.
- Stil/effekt: " . (empty($styleParts) ? 'modern, kommersiell' : implode(', ', $styleParts)) . ".
- {$overlay}
- {$title}
- Kontextuella detaljer: {$contextDetails} {$productExtras}

Tekniska krav:
- Plattformsformat: {$pfText}. Optimera komposition så att viktiga element hålls inom säkra beskärningszoner.
- Fotorealistisk/kommersiell kvalitet, hög kontrast och tydlig separation mellan motiv och bakgrund.
- Undvik vattenstämplar, onödig text och otydlig grafik.
        ");
    }
}
