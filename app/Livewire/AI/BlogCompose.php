<?php

namespace App\Livewire\AI;

use App\Jobs\GenerateContentJob;
use App\Models\AiContent;
use App\Models\ContentTemplate;
use App\Support\CurrentCustomer;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;

#[Layout('layouts.app')]
class BlogCompose extends Component
{
    public string $title = '';
    public ?int $site_id = null;
    public string $language = 'sv_SE';

    // Blogg-specifika fält
    public string $word_length = 'optimal'; // short|optimal|long
    // short ~800, optimal 800-1500, long ~1500+
    public string $keywords = '';           // fokusnyckelord (csv)
    public string $audience = '';           // målgrupp
    public string $goal = '';               // syfte med texten
    public string $brand_voice = '';        // ton/varumärkesröst

    // CTA
    public bool $include_cta = true;
    public string $cta_text = '';

    // Valfri länk i slutet
    public string $link_url = '';

    // Källa att utgå från (valfritt)
    public string $source_url = '';

    // Bild (återanvänd mönster från Compose – valfritt)
    public bool $genImage = false;
    public string $imagePromptMode = 'auto';
    public ?string $imagePrompt = null;

    #[On('active-site-updated')]
    public function onActiveSiteUpdated(?int $siteId): void
    {
        $this->site_id = $siteId;
        if ($siteId) {
            $site = \App\Models\Site::find($siteId);
            if ($site) {
                $this->language = $site->locale ?: 'sv_SE';
            }
        }
    }

    public function mount(CurrentCustomer $current): void
    {
        if ($this->site_id === null) {
            $activeSiteId = $current->getSiteId() ?? session('active_site_id') ?? session('site_id');
            $this->site_id = $activeSiteId ? (int)$activeSiteId : $current->get()?->sites()->orderBy('name')->value('id');
        }
        if ($this->site_id) {
            $site = \App\Models\Site::find($this->site_id);
            if ($site) {
                $this->language = $site->locale ?: 'sv_SE';
            }
        }
        $qTitle = request()->query('title');
        if (is_string($qTitle) && $qTitle !== '') {
            $this->title = trim($qTitle);
        }
    }

    public function submit(CurrentCustomer $current)
    {
        $this->validate([
            'title'         => 'required|string|min:3',
            'site_id'       => 'nullable|exists:sites,id',
            'language'      => 'required|in:sv_SE,en_US,de_DE',
            'word_length'   => 'required|in:short,optimal,long',
            'keywords'      => 'nullable|string|max:800',
            'audience'      => 'nullable|string|max:800',
            'goal'          => 'nullable|string|max:800',
            'brand_voice'   => 'nullable|string|max:400',
            'include_cta'   => 'boolean',
            'cta_text'      => 'nullable|string|max:200',
            'link_url'      => 'nullable|url|max:500',
            'source_url'    => 'nullable|url|max:500',
            'genImage'      => 'boolean',
            'imagePromptMode' => 'in:auto,custom',
            'imagePrompt'   => 'nullable|string|max:500',
        ]);

        if ($this->genImage && $this->imagePromptMode === 'custom' && blank($this->imagePrompt)) {
            $this->addError('imagePrompt', 'Ange en kort bildbeskrivning eller välj “Anpassa efter inlägget”.');
            return;
        }

        $customer = $current->get();
        abort_unless($customer, 403);

        // Hämta blog-mallen
        $template = ContentTemplate::where('slug', 'blog')->first();
        abort_unless($template, 422, 'Bloggmall saknas');

        // Längdspecifikation i ord (för prompten + ev. enforcement)
        $targetWords = match ($this->word_length) {
            'short'   => 'cirka 800 ord',
            'long'    => 'cirka 1500+ ord',
            default   => '800–1500 ord',
        };

        $inputs = [
            'channel'    => 'blog',
            'audience'   => $this->audience ?: null,
            'goal'       => $this->goal ?: null,
            'keywords'   => $this->keywords
                ? array_values(array_filter(array_map('trim', explode(',', $this->keywords))))
                : [],
            'brand'      => ['voice' => $this->brand_voice ?: null],
            'guidelines' => [
                'style'  => 'Informativt, engagerande och SEO‑vänligt. Använd tydliga underrubriker (H2/H3), korta stycken och gärna punktlistor.',
                'cta'    => $this->include_cta
                    ? 'Avsluta med en tydlig CTA som engagerar läsaren.'
                    : 'Ingen CTA i slutet.',
                'length' => $targetWords,
                'editor' => [
                    'headline'  => 'Skriv en lockande, kort huvudrubrik med relevanta nyckelord. Undvik VERSALER.',
                    'structure' => 'Inledning som sätter ämnet, brödtext med underrubriker, och en tydlig avslutning.',
                    'style'     => 'Anpassa efter målgrupp och varumärkesröst. Skriv kort och koncist, undvik krångliga meningar.',
                    'seo'       => 'Använd fokusnyckelord naturligt i rubriker och brödtext (utan att överoptimera).',
                    'quality'   => 'Var språkligt korrekt och konsekvent.',
                ],
            ],
            'language'   => $this->language,
            'link_url'   => $this->link_url ?: null,
            'source_url' => $this->source_url ?: null,

            // Blogg-specifika inställningar för jobbet
            'blog_settings' => [
                'word_length'  => $this->word_length,  // short|optimal|long
                'target_words' => $targetWords,        // textsträng som jobbet kan använda
                'include_cta'  => (bool) $this->include_cta,
                'cta_text'     => $this->include_cta ? (trim($this->cta_text) ?: null) : null,
            ],

            // Bild
            'image'      => [
                'generate' => $this->genImage,
                'mode'     => $this->imagePromptMode,
                'prompt'   => $this->imagePromptMode === 'custom' ? $this->imagePrompt : null,
            ],
        ];

        // Kostnad: längre blogg = long
        $tone = ($this->word_length === 'short') ? 'short' : 'long';
        $cost = ($tone === 'short') ? 10 : 50;

        try {
            app(\App\Services\Billing\QuotaGuard::class)->checkCreditsOrFail($customer, $cost, 'credits');
        } catch (\Throwable $e) {
            $this->addError('general', $e->getMessage());
            return;
        }

        $content = AiContent::create([
            'customer_id' => $customer->id,
            'site_id'     => $this->site_id,
            'template_id' => $template->id,
            'title'       => $this->title,
            'tone'        => $tone,
            'status'      => 'queued',
            'inputs'      => $inputs,
        ]);

        dispatch(new GenerateContentJob($content->id))->onQueue('ai');

        return redirect()->route('ai.detail', $content->id)
            ->with('success', 'Generering av blogginlägg påbörjad.');
    }

    public function render(CurrentCustomer $current): View
    {
        $sites = $current->get()?->sites()->orderBy('name')->get() ?? collect();
        return view('livewire.a-i.blog-compose', compact('sites'));
    }
}
