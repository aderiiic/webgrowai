<?php

namespace App\Livewire\AI\Images;

use App\Jobs\GenerateAiImageJob;
use App\Models\AiContent;
use App\Models\ImageAsset;
use App\Models\Site;
use App\Support\CurrentCustomer;
use App\Support\Usage;
use App\Services\Billing\PlanService;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;

#[Layout('layouts.app')]
class Generator extends Component
{
    public ?int $site_id = null;

    // Formfält – utökat
    public string $image_type = 'Produktbild'; // motivscenario
    public string $campaign_type = 'none';     // none|launch|sale|seasonal|ugc|editorial|hero
    public string $goal = 'attention';         // attention|sell|educate|announce|retarget
    public string $product_category = '';      // t.ex. skor, smink, kläder
    public string $background_mode = 'white';  // white|black|gray|solid|brand|gradient|pattern|marble|concrete|wood|studio|lifestyle_in|lifestyle_out|custom_hex
    public string $background_hex = '';        // #RRGGBB när background_mode=custom_hex
    public bool   $strict_background = true;   // extra hård kravställning på slät bakgrund
    public string $style = '';                 // icy|blur|clean|minimal|branding-anpassad|vintage|filmic|high-contrast
    public string $title = '';
    public bool   $overlay_enabled = false;
    public string $overlay_text = '';
    public string $overlay_position = 'auto';  // auto|top|bottom|left|right|center|safe-top|safe-bottom
    public string $platform = 'facebook_square';

    // Generera från befintligt inlägg
    public bool $from_post_enabled = false;
    public ?int $from_post_id = null;
    public array $availablePosts = []; // [{id,title}]

    public string $product_item = '';            // "flaska", "diskmaskin", "bok", ...
    public string $logo_url = '';               // https://... .png (transparent)
    public bool $label_text_enabled = false;    // om text ska finnas på produkten/etiketten
    public string $label_text = '';             // frivillig etikett-text
    public string $text_language = 'svenska';

    // UI state
    public bool $busy = false;
    public bool $queued = false;
    public string $error = '';
    public string $finalPrompt = '';
    public array $items = []; // [{id, name}]
    private int $lastCount = 0;

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

            // Ladda valbara inlägg (senaste 50 för aktiv kund, gärna per sajt om satt)
            $postsQ = AiContent::query()->where('customer_id', (int) $customer->id);
            if ($this->site_id) {
                $postsQ->where('site_id', $this->site_id);
            }
            $posts = $postsQ->orderByDesc('created_at')->take(50)->get(['id','title']);
            $this->availablePosts = $posts->map(fn($p) => ['id' => $p->id, 'title' => $p->title ?: ('Inlägg #' . $p->id)])->all();
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
            'image_type'        => 'required|string|max:50',
            'campaign_type'     => 'required|in:none,launch,sale,seasonal,ugc,editorial,hero',
            'goal'              => 'required|in:attention,sell,educate,announce,retarget',
            'product_category'  => 'nullable|string|max:120',
            'background_mode'   => 'required|in:white,black,gray,solid,brand,gradient,pattern,marble,concrete,wood,studio,lifestyle_in,lifestyle_out,custom_hex',
            'background_hex'    => 'nullable|regex:/^#?[0-9A-Fa-f]{6}$/',
            'strict_background' => 'boolean',
            'style'             => 'nullable|string|max:100',
            'title'             => 'nullable|string|max:180',
            'overlay_enabled'   => 'boolean',
            'overlay_text'      => 'nullable|required_if:overlay_enabled,true|string|max:200',
            'overlay_position'  => 'required|in:auto,top,bottom,left,right,center,safe-top,safe-bottom',
            'platform'          => 'required|in:facebook_square,facebook_story,instagram,linkedin,blog',
            'from_post_enabled' => 'boolean',
            'from_post_id'      => 'nullable|required_if:from_post_enabled,true|integer|exists:ai_contents,id',
        ]);

        if ($this->image_type === 'Produktbild') {
            $this->validate([
                'product_item'        => 'nullable|string|max:120',
                'logo_url'            => 'nullable|url|max:500',
                'label_text_enabled'  => 'boolean',
                'label_text'          => 'nullable|required_if:label_text_enabled,true|string|max:120',
                'text_language'       => 'required|string|in:svenska,engelska,norska,danska,finska,tyska,franska,spanska,italienska',
            ]);
            // Enkel PNG-hint (mjuk validering) – bara varning i UI via prompt; här kan vi lättrensa space
            $this->logo_url = trim($this->logo_url);
        } else {
            // För andra bildtyper validerar vi logotyp-URL separat
            $this->validate([
                'logo_url' => 'nullable|url|max:500',
            ]);
            $this->logo_url = trim($this->logo_url);
        }

        if ($this->background_mode === 'custom_hex' && empty($this->background_hex)) {
            $this->addError('background_hex', 'Ange en färgkod i formatet #RRGGBB.');
            return;
        }

        if ($this->from_post_enabled && empty($this->from_post_id)) {
            $this->addError('from_post_id', 'Välj vilket inlägg som bilden ska utgå ifrån.');
            return;
        }

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
        $post = null;
        if ($this->from_post_enabled && $this->from_post_id) {
            $post = AiContent::query()
                ->where('id', (int) $this->from_post_id)
                ->where('customer_id', (int) $customer->id)
                ->first();
        }

        $this->finalPrompt = $this->buildPrompt($site, $post, true);
        $aiPrompt = $this->buildPrompt($site, $post, false);

        $this->busy = true;
        $this->queued = true;

        dispatch(new \App\Jobs\GenerateAiImageJob(
            customerId: (int) $customer->id,
            siteId: $site->id,
            prompt: $aiPrompt, // <-- textfri prompt skickas till DALL·E
            platform: $this->platform,
            logoUrl: $this->logo_url ?: null,
            overlayEnabled: true, // tvinga overlay om text finns
            overlayText: $this->overlay_text ?: null,
            overlayPosition: $this->overlay_position,
            textLanguage: $this->text_language ?: 'svenska'
        ))->afterCommit()->onQueue('ai');

        // UI-feedback
        $this->used = $used + 1;
        $this->remaining = is_null($quota) ? null : max(0, $quota - $this->used);
        $this->aiEnabled = is_null($quota) ? true : ($quota > 0);

        // Polla listan
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

        $newCount = count($this->items);
        if ($this->queued && $newCount > $this->lastCount) {
            $this->queued = false;
        }
        $this->lastCount = $newCount;
    }

    public function openPreview(int $id): void
    {
        $customer = app(CurrentCustomer::class)->get();
        if (!$customer) return;

        $asset = ImageAsset::query()
            ->where('id', $id)
            ->where('customer_id', $customer->id) // Säkerhetscheck
            ->first();

        if (!$asset) return;

        $this->previewId = $asset->id;
        $this->previewName = $asset->original_name ?: ('Bild #' . $asset->id);

        try {
            $this->previewUrl = Storage::disk($asset->disk ?? 's3')
                ->temporaryUrl($asset->path, now()->addMinutes(10));
        } catch (\Throwable $e) {
            try {
                $this->previewUrl = Storage::disk($asset->disk ?? 's3')->url($asset->path);
            } catch (\Throwable $e) {
                $this->previewUrl = '';
                $this->error = 'Kunde inte ladda bildens förhandsvisning.';
                return;
            }
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

    public function updatedImageType(string $value): void
    {
        // Rensa produktspecifika fält när bildtyp ändras från Produktbild
        if ($value !== 'Produktbild') {
            $this->product_item = '';
            $this->label_text_enabled = false;
            $this->label_text = '';
            $this->text_language = 'svenska';
        }
    }

    public function updatedBackgroundMode(string $value): void
    {
        if ($value !== 'custom_hex') {
            $this->background_hex = '';
        }
    }

    public function updatedFromPostEnabled(bool $value): void
    {
        if ($value === false) {
            $this->from_post_id = null;
        }
    }

    public function updatedLabelTextEnabled(bool $value): void
    {
        if ($value === false) {
            $this->label_text = '';
        }
    }

    public function updatedOverlayEnabled(bool $value): void
    {
        if ($value === false) {
            $this->overlay_text = '';
            $this->overlay_position = 'auto';
        }
    }

    private function buildPrompt(Site $site, ?AiContent $post = null, bool $allowOverlayInImage = false): string
    {
        // 1) Motivscenarier
        $motifHints = [
            'Produktbild'    => 'Packshot/e-handel: produkten i centrum, säljinriktat, rent formspråk.',
            'Facebook-bild'  => 'Social-first layout, tydlig hook, hög läsbarhet.',
            'LinkedIn-bild'  => 'Professionell, förtroendeingivande, ren layout.',
            'Bloggbild'      => 'Hero/header som stödjer ämnet, modern estetik.',
            'Instagrambild'  => 'Estetiskt stark, trendsäker, porträtt-optimerad.',
            'Kampanjbild'    => 'Kampanjfokus: tydlig visuell idé som driver CTA.',
        ];
        $motif = $motifHints[$this->image_type] ?? $this->image_type;

        // 2) Kampanjtyp
        $campaignMap = [
            'none'      => 'Ingen specifik kampanj: varumärkesdrivet bildspråk.',
            'launch'    => 'Lanseringskänsla: nyhet, innovation, premium.',
            'sale'      => 'Rea/erbjudande: kraftfull komposition, fokus på produkten.',
            'seasonal'  => 'Säsongstema: färger/rekvisita som subtilt speglar säsongen.',
            'ugc'       => 'UGC-känsla: autentisk, verklig, men estetiskt balanserad.',
            'editorial' => 'Premium editorial: magasinkänsla, sofistikerat ljus.',
            'hero'      => 'Hero banner: stark komposition med central fokuspunkt.',
        ];
        $campaignHint = $campaignMap[$this->campaign_type] ?? $campaignMap['none'];

        // 3) Mål
        $goalMap = [
            'attention' => 'Mål: fånga uppmärksamhet direkt i flödet.',
            'sell'      => 'Mål: driva försäljning – tydliga produktfördelar och köplust.',
            'educate'   => 'Mål: informera/utbilda om nyttan.',
            'announce'  => 'Mål: annonsera en nyhet/lansering.',
            'retarget'  => 'Mål: retargeting – igenkänning och konverteringsdriv.',
        ];
        $goalHint = $goalMap[$this->goal] ?? $goalMap['attention'];

        // 4) Bakgrund
        $hex = ltrim($this->background_hex, '#');
        $hex = strlen($hex) === 6 ? '#' . strtoupper($hex) : '';
        $bgHints = [
            'white'         => 'Helt ren vit bakgrund (#FFFFFF). Ingen textur, gradient eller vinjettering.',
            'black'         => 'Djup svart bakgrund (#000000), helt utan mönster.',
            'gray'          => 'Neutral grå bakgrund (t.ex. #F2F2F2), subtil studioskugga tillåten.',
            'solid'         => 'Solid färgbakgrund som harmoniserar med varumärket.',
            'brand'         => 'Bakgrund i varumärkets färger/bildspråk.',
            'gradient'      => 'Mycket mjuk gradient utan bandning.',
            'pattern'       => 'Diskret mönster, minimalistiskt och icke-distraherande.',
            'marble'        => 'Ljus marmoryta, subtil och elegant.',
            'concrete'      => 'Ljus betongyta, svagt texturerad.',
            'wood'          => 'Neutral ljus träyta.',
            'studio'        => 'Studio backdrop, mjuk övergång, professionellt ljus.',
            'lifestyle_in'  => 'Inomhus lifestyle-miljö, modern, naturligt ljus.',
            'lifestyle_out' => 'Utomhus lifestyle-miljö, naturligt ljus.',
            'custom_hex'    => $hex ? "Solid bakgrund i exakt färg {$hex}, helt utan textur/gradient." : "Solid bakgrund i angiven färg, helt utan textur/gradient.",
        ];
        $bgHint = $bgHints[$this->background_mode] ?? $bgHints['white'];

        $strictBg = $this->strict_background
            ? "ABSOLUT krav: bakgrunden ska exakt följa ovanstående. Inga mönster, gradienter, vinjettering eller artefakter. Texten ska vara på svenska!"
            : "Bakgrunden ska följa riktlinjen ovan.";

        // 5) Stil
        $styleMap = [
            'icy'               => 'kylig, krispig ton, svagt blåskimmer, hög klarhet',
            'blur'              => 'svag bakgrundsblur för djupkänsla',
            'clean'             => 'minimalistisk, ren, med luftigt negativt utrymme',
            'minimal'           => 'få element, elegant, fokus på huvudmotiv',
            'branding-anpassad' => 'harmoniserar med varumärkets färger och formspråk',
            'vintage'           => 'diskret vintage/filmisk ton utan tappad premiumkänsla',
            'filmig'            => 'cinematisk ljussättning och kontrast',
            'high-contrast'     => 'hög kontrast, tydlig separation',
        ];
        $styleParts = [];
        if ($this->style !== '') {
            foreach (explode(',', $this->style) as $s) {
                $s = trim($s);
                $styleParts[] = $styleMap[$s] ?? $s;
            }
        }
        $styleText = empty($styleParts) ? 'modern, kommersiell stil' : implode(', ', $styleParts);

        // 6) Overlay-text
        if ($allowOverlayInImage && $this->overlay_enabled && $this->overlay_text !== '') {
            $overlay = 'Lägg in en text-overlay: "' . $this->overlay_text . '". Placering: ' . $this->overlay_position .
                '. Hög läsbarhet, korrekt kontrast, korrekt språk och stavning. Ingen annan text får förekomma.';
        } else {
            $overlay = 'Modellen får under inga omständigheter generera ord, bokstäver eller etiketter i bilden. ' .
                'Bilden ska vara helt textfri. All text läggs efteråt i systemets kod.';
        }

        // 7) Titel/tema + kategori
        $title = $this->title !== '' ? "Titel/tema: {$this->title}." : "Ingen explicit titel.";
        $category = $this->product_category ? "Produktkategori: {$this->product_category}." : '';

        // 8) Kontext (Site + ev. inlägg)
        $siteContext = "Varumärkesröst: " . ($site->effectiveBrandVoice() ?: '-') .
            ". Målgrupp: " . ($site->effectiveAudience() ?: '-') .
            ". Mål: " . ($site->effectiveGoal() ?: '-') .
            ". Nyckelord: " . implode(', ', $site->effectiveKeywords() ?: []);

        $postContext = '';
        if ($post) {
            $body = trim((string) ($post->body_md ?? ''));
            $summary = Str::limit(strip_tags($body), 300);
            $postContext = "Anpassa till inlägget \"{$post->title}\". Sammanfattning: {$summary}";
        }

        // 9) Plattform
        $pfText = match ($this->platform) {
            'facebook_square' => 'Facebook 1080x1080',
            'facebook_story'  => 'Facebook/Instagram Story 1080x1920',
            'instagram'       => 'Instagram 1080x1350',
            'linkedin'        => 'LinkedIn 1080x1080',
            'blog'            => 'Blogg 1200x628 (1.91:1)',
            default           => $this->platform
        };

        // 10) Produktdetaljer
        $productExtras = (stripos($this->image_type, 'produkt') !== false || stripos($this->product_category, 'dryck') !== false)
            ? "Om dryck/flaska förekommer: realistiska vattendroppar, fräsch och läskande känsla."
            : "";

        // 11) Negativa instruktioner
        $negatives = "Förbjudet: extra text, nonsensord, pseudo-typografi, vattenstämplar, bandningar, artefakter, felaktiga skuggor, pixlighet eller överdrivna filter.";
        if ($this->image_type === 'Produktbild') {
            if ($this->label_text_enabled) {
                $negatives .= " Endast exakt etiketttext enligt angivelse. Ingen annan text på produkten.";
            } else {
                $negatives .= " Ingen text på produkten eller etiketten överhuvudtaget.";
            }
        }

        // 12) Logotyp
        $logoInstr = '';
        $logo = trim($this->logo_url);
        if ($logo !== '') {
            $logoInstr = "Integrera logotyp (transparent PNG): {$logo}. Placera naturligt, inte som watermark. På produktbilder: på etiketten. Annars diskret i kompositionen.";
        }

        // 13) Produktblock
        $productBlock = '';
        if ($this->image_type === 'Produktbild') {
            $item = trim($this->product_item) !== '' ? $this->product_item : 'produkt (specificera form tydligt)';
            $langMap = [
                'svenska'   => 'svenska',
                'engelska'  => 'engelska',
                'norska'    => 'norska',
                'danska'    => 'danska',
                'finska'    => 'finska',
                'tyska'     => 'tyska',
                'franska'   => 'franska',
                'spanska'   => 'spanska',
                'italienska'=> 'italienska',
            ];
            $lang = $langMap[$this->text_language] ?? 'svenska';
            $labelInstr = $this->label_text_enabled
                ? 'Etiketttext: "' . $this->label_text . '". Måste vara korrekt och på ' . $lang . '.'
                : 'Om etiketttext används: kort, säljande text på ' . $lang . ', med hög läsbarhet.';

            $productBlock = "
Produktbildsspecifik:
- Produkttyp: {$item}.
- {$labelInstr}
- All text (overlay, etikett, logotyp) ska vara på {$lang}.
- Säkerställ korrekt logotypåtergivning och realistisk yta/kurvatur.";
        }

        // Slutlig prompt
        return trim("
Skapa en professionell och säljbar bild med följande krav:

Motivscenario:
- {$motif}
- {$campaignHint}
- {$goalHint}
- {$category}

Bakgrund:
- {$bgHint}
- {$strictBg}

Stil/effekt:
- {$styleText}

Text & overlay:
- {$overlay}

Titel/tema:
- {$title}
" . ($logoInstr ? "\nLogotyp:\n- {$logoInstr}\n" : "") . "
Kontext:
- {$siteContext}
" . ($postContext ? "- {$postContext}\n" : "") . "

Plattform:
- {$pfText}. Anpassa kompositionen så att motiv och overlay ligger inom säkra beskärningszoner.

Kvalitet & utförande:
- Fotorealistisk, kommersiell premiumkvalitet.
- Professionell ljussättning och tydlig separation mellan motiv och bakgrund.
- {$productExtras}

{$negatives}
{$productBlock}
    ");
    }
}
