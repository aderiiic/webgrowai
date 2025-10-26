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
class ProductCompose extends Component
{
    public ?int $site_id = null;
    public string $language = 'sv_SE';

    public string $product_title = '';
    public string $product_description = '';
    public string $features = '';     // csv eller multiline
    public string $usps = '';         // csv eller multiline
    public string $audience = '';
    public string $brand_voice = '';
    public string $length = 'optimal'; // short|optimal|long

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
    }

    public function submit(CurrentCustomer $current)
    {
        $this->validate([
            'product_title'       => 'required|string|min:2|max:160',
            'product_description' => 'nullable|string|max:4000',
            'features'            => 'nullable|string|max:4000',
            'usps'                => 'nullable|string|max:4000',
            'audience'            => 'nullable|string|max:500',
            'brand_voice'         => 'nullable|string|max:400',
            'length'              => 'required|in:short,optimal,long',
            'site_id'             => 'nullable|exists:sites,id',
            'language'            => 'required|in:sv_SE,en_US,de_DE',
        ]);

        $customer = $current->get();
        abort_unless($customer, 403);

        $template = ContentTemplate::where('slug', 'blog')->first() ?? ContentTemplate::orderBy('id')->first();
        abort_unless($template, 422, 'Mall saknas');

        $inputs = [
            'channel'    => 'product',
            'audience'   => $this->audience ?: null,
            'goal'       => 'Create a compelling, SEO‑friendly product description that converts.',
            'keywords'   => [], // kan läggas till senare vid behov
            'brand'      => ['voice' => $this->brand_voice ?: null],
            'guidelines' => [
                'style'  => 'Säljande men saklig. Använd H2/H3 och punktlistor för features/benefits. SEO‑vänlig rubrik och meta.',
                'length' => match ($this->length) {
                    'short'   => 'ca 100–200 ord',
                    'long'    => 'ca 400–700 ord',
                    default   => 'ca 200–400 ord',
                },
                'cta'    => 'Avsluta med tydlig uppmaning (köp, lägg i varukorg, kontakta).',
            ],
            'language'   => $this->language,
            'link_url'   => null,
            'source_url' => null,
            'product'    => [
                'title'       => $this->product_title,
                'description' => $this->product_description ?: null,
                'features'    => $this->normalizeList($this->features),
                'usps'        => $this->normalizeList($this->usps),
            ],
        ];

        $tone = in_array($this->length, ['short'], true) ? 'short' : 'long';
        $cost = match ($this->length) {
            'short'   => 10,
            'long'    => 50,
            default   => 25,
        };

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
            'title'       => $this->product_title,
            'tone'        => $tone,
            'status'      => 'queued',
            'inputs'      => $inputs,
        ]);

        dispatch(new GenerateContentJob($content->id))->onQueue('ai');

        return redirect()->route('ai.detail', $content->id)
            ->with('success', 'Produkttext generering påbörjad.');
    }

    private function normalizeList(string $raw): array
    {
        if (trim($raw) === '') return [];
        $parts = preg_split('/[\r\n,]+/', $raw);
        return array_values(array_filter(array_map('trim', $parts)));
    }

    public function render(CurrentCustomer $current): View
    {
        $sites = $current->get()?->sites()->orderBy('name')->get() ?? collect();
        return view('livewire.a-i.product-compose', compact('sites'));
    }
}
