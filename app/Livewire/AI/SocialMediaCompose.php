<?php

namespace App\Livewire\AI;

use App\Jobs\GenerateContentJob;
use App\Models\AiContent;
use App\Models\ContentTemplate;
use App\Services\Billing\FeatureGuard;
use App\Support\CurrentCustomer;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;

#[Layout('layouts.app')]
class SocialMediaCompose extends Component
{
    public string $title = '';
    public string $platform = '';
    public string $length = 'optimal';
    public string $cta = '';
    public string $link_url = '';
    public bool $include_hashtags = true;
    public ?int $site_id = null;
    public string $language = 'sv_SE';

    // Lägg till fält för avancerade inställningar
    public string $audience = '';
    public string $goal = '';
    public string $keywords = '';
    public string $brand_voice = '';

    public bool $platformChanged = false;

    // Platform-specific length configurations
    public array $lengthConfigs = [
        'facebook' => [
            'optimal' => '40-80 tecken',
            'short' => '20-39 tecken',
            'medium' => '81-150 tecken',
            'long' => '151-300+ tecken'
        ],
        'instagram' => [
            'optimal' => '75-150 tecken',
            'short' => '1-74 tecken',
            'medium' => '151-300 tecken',
            'long' => '301-500+ tecken'
        ],
        'linkedin' => [
            'optimal' => '1200-1600 tecken',
            'short' => '0-300 tecken',
            'medium' => '301-1200 tecken',
            'long' => '1601-2000+ tecken'
        ]
    ];

    public function getLengthMap(): array
    {
        // Returnera en ny array-referens så Livewire ser förändring
        return [
            'facebook' => $this->lengthConfigs['facebook'],
            'instagram' => $this->lengthConfigs['instagram'],
            'linkedin' => $this->lengthConfigs['linkedin'],
        ];
    }

    public function mount(CurrentCustomer $current): void
    {
        $customer = $current->get();
        abort_unless($customer, 403);

        // Kontrollera feature-access och redirecta om nekad
        if (!app(FeatureGuard::class)->canUseFeature($customer, FeatureGuard::FEATURE_SOCIAL_MEDIA)) {
            $this->redirect(route('feature.locked', ['feature' => FeatureGuard::FEATURE_SOCIAL_MEDIA]), navigate: false);
            return;
        }

        // Set default site
        if ($this->site_id === null) {
            $activeSiteId = $current->getSiteId() ?? session('active_site_id') ?? session('site_id');
            if ($activeSiteId) {
                $this->site_id = (int) $activeSiteId;
            } else {
                $this->site_id = $current->get()?->sites()->orderBy('name')->value('id');
            }
        }

        // Initialize language from selected site
        if ($this->site_id) {
            $site = \App\Models\Site::find($this->site_id);
            if ($site) {
                $this->language = $site->locale ?: 'sv_SE';
            }
        }

        // Get platform from query parameter
        $this->platform = request()->query('platform', 'facebook');

        // Get title from query parameter
        $qTitle = request()->query('title');
        if (is_string($qTitle) && $qTitle !== '') {
            $this->title = trim($qTitle);
        }
    }

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

    // Lägg till en metod för att uppdatera vyn när plattform ändras
    public function updatedPlatform(): void
    {
        // Detta triggar en re-render så att längderna uppdateras
        $this->length = 'optimal';
        $this->platformChanged = !$this->platformChanged;
    }

    public function submit(CurrentCustomer $current)
    {
        $this->validate([
            'title' => 'required|string|min:3',
            'platform' => 'required|in:facebook,instagram,linkedin',
            'length' => 'required|in:optimal,short,medium,long',
            'cta' => 'nullable|string|max:200',
            'link_url' => 'nullable|url|max:500',
            'include_hashtags' => 'boolean',
            'site_id' => 'nullable|exists:sites,id',
            'language' => 'required|in:sv_SE,en_US,de_DE',
            'audience' => 'nullable|string|max:500',
            'goal' => 'nullable|string|max:500',
            'keywords' => 'nullable|string|max:500',
            'brand_voice' => 'nullable|string|max:500',
        ]);

        $customer = $current->get();
        abort_unless($customer, 403);

        // Find the appropriate template based on platform
        $templateSlug = "social-{$this->platform}";
        $template = ContentTemplate::where('slug', $templateSlug)->first();

        if (!$template) {
            // Fallback to a generic social template
            $template = ContentTemplate::where('slug', 'social-facebook')->first();
        }

        abort_unless($template, 422, 'Social media template not found');

        // Build AI guidelines based on platform and length
        $guidelines = $this->buildGuidelines();

        $lengthText = $this->lengthConfigs[$this->platform][$this->length] ?? null;

        $inputs = [
            'channel' => $this->platform,
            'audience' => $this->audience ?: null,
            'goal' => $this->goal ?: ($this->cta ? "Include this call-to-action: {$this->cta}" : null),
            'keywords' => $this->keywords ? array_values(array_filter(array_map('trim', explode(',', $this->keywords)))) : [],
            'brand' => ['voice' => $this->brand_voice ?: null],
            'guidelines' => $guidelines,
            'language' => $this->language,
            'link_url' => $this->link_url ?: null,
            'social_settings' => [
                'platform' => $this->platform,
                'length' => $this->length,
                'length_chars' => $lengthText,
                'include_hashtags' => $this->include_hashtags,
                'cta' => $this->cta ?: null,
            ],
        ];

        // Determine cost and tone based on length
        $cost = $this->getPostCost();
        $tone = $this->getToneFromLength();

        try {
            app(\App\Services\Billing\QuotaGuard::class)->checkCreditsOrFail($customer, $cost, 'credits');
        } catch (\Throwable $e) {
            $this->addError('general', $e->getMessage());
            return;
        }

        $content = AiContent::create([
            'customer_id' => $customer->id,
            'site_id' => $this->site_id,
            'template_id' => $template->id,
            'title' => $this->title,
            'tone' => $tone,
            'type' => 'social',
            'status' => 'queued',
            'inputs' => $inputs,
        ]);

        dispatch(new GenerateContentJob($content->id))->onQueue('ai');

        return redirect()->route('ai.detail', $content->id)
            ->with('success', 'Generering påbörjad.');
    }

    private function buildGuidelines(): array
    {
        $platformGuidelines = match ($this->platform) {
            'facebook' => [
                'style' => 'Conversational, lätt, 1–2 korta stycken, 1–2 emojis max.',
                'cta' => 'Uppmana till enkel handling (läs mer, kommentera, skicka DM).',
                'hashtags' => $this->include_hashtags ? '0–3 hashtags.' : 'Inga hashtags.',
            ],
            'instagram' => [
                'style' => 'Berättande ton, radbrytningar för läsbarhet.',
                'cta' => 'Uppmana till interaktion (spara/dela/DM).',
                'hashtags' => $this->include_hashtags ? '5–10 relevanta hashtags i slutet.' : 'Inga hashtags.',
            ],
            'linkedin' => [
                'style' => 'Professionell, saklig, 2–4 korta stycken.',
                'cta' => 'Uppmana till insikt/kommentar.',
                'hashtags' => $this->include_hashtags ? '1–3 hashtags.' : 'Inga hashtags.',
            ],
            default => [
                'style' => 'Anpassa efter plattform.',
                'cta' => 'Tydlig CTA.',
                'hashtags' => $this->include_hashtags ? 'Relevanta hashtags.' : 'Inga hashtags.',
            ],
        };

        $lengthGuideline = $this->lengthConfigs[$this->platform][$this->length] ?? 'Standard längd';
        $platformGuidelines['length'] = $lengthGuideline;

        return $platformGuidelines;
    }

    private function getPostCost(): int
    {
        return match ($this->length) {
            'short', 'optimal' => 10,
            'medium' => 25,
            'long' => 50,
            default => 10,
        };
    }

    private function getToneFromLength(): string
    {
        return match ($this->length) {
            'short', 'optimal' => 'short',
            'medium', 'long' => 'long',
            default => 'short',
        };
    }

    public function getLengthDescription(): string
    {
        if (!$this->platform) {
            return 'Välj plattform först';
        }

        return $this->lengthConfigs[$this->platform][$this->length] ?? '';
    }

    public function render(CurrentCustomer $current): View
    {
        $sites = $current->get()?->sites()->orderBy('name')->get() ?? collect();
        $lengthDescription = $this->getLengthDescription();
        $lengthMap = $this->getLengthMap();

        return view('livewire.a-i.social-media-compose', compact('sites', 'lengthDescription', 'lengthMap'));
    }
}
