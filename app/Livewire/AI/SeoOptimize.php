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
class SeoOptimize extends Component
{
    public ?int $site_id = null;
    public string $language = 'sv_SE';

    public string $title = '';           // Kort rubrik på uppdraget
    public string $original_text = '';   // Text som ska optimeras
    public string $keywords = '';        // csv
    public string $brand_voice = '';     // valfritt

    public string $goal = 'Improve SEO and readability without losing meaning';

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
        $customer = $current->get();
        abort_unless($customer, 403);

        // Kontrollera feature-access och redirecta om nekad
        if (!app(FeatureGuard::class)->canUseFeature($customer, FeatureGuard::FEATURE_SEO_OPTIMIZE)) {
            $this->redirect(route('feature.locked', ['feature' => FeatureGuard::FEATURE_SEO_OPTIMIZE]), navigate: false);
            return;
        }

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
            'title'         => 'required|string|min:3|max:160',
            'original_text' => 'required|string|min:30|max:60000',
            'keywords'      => 'nullable|string|max:1000',
            'brand_voice'   => 'nullable|string|max:400',
            'site_id'       => 'nullable|exists:sites,id',
            'language'      => 'required|in:sv_SE,en_US,de_DE',
        ]);

        $customer = $current->get();
        abort_unless($customer, 403);

        // Template: återanvänd "blog" eller skapa separat "seo-optimize" om finns
        $template = ContentTemplate::where('slug', 'blog')->first() ?? ContentTemplate::orderBy('id')->first();
        abort_unless($template, 422, 'Mall saknas');

        $kw = $this->keywords
            ? array_values(array_filter(array_map('trim', explode(',', $this->keywords))))
            : [];

        $inputs = [
            'channel'    => 'seo',
            'audience'   => null,
            'goal'       => $this->goal,
            'keywords'   => $kw,
            'brand'      => ['voice' => $this->brand_voice ?: null],
            'guidelines' => [
                'style'  => 'Behåll faktainnehåll, förbättra läsbarhet, struktur och SEO. Använd underrubriker/punktlistor vid behov.',
                'length' => 'Liknande eller något längre än original om det förbättrar tydlighet.',
                'cta'    => 'Ingen explicit CTA om inte naturligt.',
            ],
            'language'   => $this->language,
            'link_url'   => null,
            'source_url' => null,
            'seo_optimize' => [
                'original_text' => $this->original_text,
            ],
        ];

        // Kreditkostnad baserat på teckenlängd
        $len = mb_strlen($this->original_text);
        $cost = $this->costForLength($len);
        $tone = $len <= 5000 ? 'short' : 'long';

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
            'type'        => 'seo',
            'status'      => 'queued',
            'inputs'      => $inputs,
        ]);

        dispatch(new GenerateContentJob($content->id))->onQueue('ai');

        return redirect()->route('ai.detail', $content->id)
            ->with('success', 'SEO‑optimering påbörjad.');
    }

    private function costForLength(int $len): int
    {
        return $len <= 5000 ? 10 : ($len <= 12000 ? 25 : 50);
    }

    public function render(CurrentCustomer $current): View
    {
        $sites = $current->get()?->sites()->orderBy('name')->get() ?? collect();
        return view('livewire.a-i.seo-optimize', compact('sites'));
    }
}
