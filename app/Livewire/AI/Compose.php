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
class Compose extends Component
{
    public ?int $template_id = null;
    public string $title = '';
    public string $tone = 'short'; // short|long
    public ?int $site_id = null;

    public string $audience = '';
    public string $goal = '';
    public string $keywords = '';
    public string $brand_voice = '';

    public string $channel = 'auto';

    public bool $genImage = false;
    public string $imagePromptMode = 'auto'; // auto|custom
    public ?string $imagePrompt = null;

    public function mount(CurrentCustomer $current): void
    {
        if ($this->site_id === null) {
            $activeSiteId = $current->getSiteId() ?? session('active_site_id') ?? session('site_id');
            if ($activeSiteId) {
                $this->site_id = (int) $activeSiteId;
            } else {
                $this->site_id = $current->get()?->sites()->orderBy('name')->value('id');
            }
        }

        $qTitle = request()->query('title');
        if (is_string($qTitle) && $qTitle !== '') {
            $this->title = trim($qTitle);
        }
    }

    // Nytt: lyssna på topbarens event och uppdatera valet live
    #[On('active-site-updated')]
    public function onActiveSiteUpdated(?int $siteId): void
    {
        $this->site_id = $siteId;
    }

    public function submit(CurrentCustomer $current)
    {
        $this->validate([
            'template_id'     => 'required|exists:content_templates,id',
            'title'           => 'required|string|min:3',
            'tone'            => 'required|in:short,long',
            'site_id'         => 'nullable|exists:sites,id',
            'channel'         => 'nullable|in:auto,blog,facebook,instagram,linkedin,campaign',
            'genImage'        => 'boolean',
            'imagePromptMode' => 'in:auto,custom',
            'imagePrompt'     => 'nullable|string|max:500',
        ]);

        if ($this->genImage && $this->imagePromptMode === 'custom' && blank($this->imagePrompt)) {
            $this->addError('imagePrompt', 'Ange en kort bildbeskrivning eller välj “Anpassa efter inlägget”.');
            return;
        }

        $customer = $current->get();
        abort_unless($customer, 403);

        $tpl = ContentTemplate::find($this->template_id);
        abort_unless($tpl, 422);

        $mapped = $this->channelFromTemplateSlug($tpl->slug);
        $finalChannel = $mapped ?: ($this->channel !== 'auto' ? $this->channel : 'auto');

        $guidelines = $this->guidelinesFor($finalChannel);

        $inputs = [
            'channel'   => $finalChannel,
            'audience'  => $this->audience ?: null,
            'goal'      => $this->goal ?: null,
            'keywords'  => $this->keywords ? array_values(array_filter(array_map('trim', explode(',', $this->keywords)))) : [],
            'brand'     => ['voice' => $this->brand_voice ?: null],
            'guidelines'=> $guidelines,
            'image'     => [
                'generate' => $this->genImage,
                'mode'     => $this->imagePromptMode,
                'prompt'   => $this->imagePromptMode === 'custom' ? $this->imagePrompt : null,
            ],
        ];

        $content = AiContent::create([
            'customer_id' => $customer->id,
            'site_id'     => $this->site_id,
            'template_id' => $this->template_id,
            'title'       => $this->title,
            'tone'        => $this->tone,
            'status'      => 'queued',
            'inputs'      => $inputs,
        ]);

        dispatch(new GenerateContentJob($content->id))->onQueue('ai');

        return redirect()->route('ai.detail', $content->id)
            ->with('success', 'Generering påbörjad.');
    }

    private function channelFromTemplateSlug(?string $slug): ?string
    {
        return match ($slug) {
            'social-facebook'  => 'facebook',
            'social-instagram' => 'instagram',
            'social-linkedin'  => 'linkedin',
            'blog'             => 'blog',
            'campaign'         => 'campaign',
            default            => null,
        };
    }

    private function guidelinesFor(string $channel): array
    {
        return match ($channel) {
            'facebook' => [
                'style' => 'Conversational, lätt, 1–2 korta stycken, 1–2 emojis max, 0–3 hashtags.',
                'cta'   => 'Uppmana till enkel handling (läs mer, kommentera, skicka DM).',
                'length'=> 'Max ~100–150 ord.',
            ],
            'instagram' => [
                'style' => 'Berättande ton, radbrytningar, 5–10 relevanta hashtags i slutet.',
                'cta'   => 'Uppmana till interaktion (spara/dela/DM).',
                'length'=> 'Max ~150–220 ord.',
            ],
            'linkedin' => [
                'style' => 'Professionell, saklig, 2–4 korta stycken, 1–3 hashtags.',
                'cta'   => 'Uppmana till insikt/kommentar.',
                'length'=> 'Max ~1 300 tecken.',
            ],
            'blog' => [
                'style' => 'Informativt och SEO‑vänligt med underrubriker (H2/H3), gärna punktlistor.',
                'cta'   => 'Avsluta med nästa steg/länkar.',
                'length'=> 'Längre format (ca 600–1 200 ord).',
            ],
            'campaign' => [
                'style' => 'Idélista med 3–5 koncept: målgrupp, budskap, hook, CTA, ev. annonseringsvinkel.',
                'cta'   => 'Konkreta nästa steg.',
                'length'=> 'Korta punktlistor per idé.',
            ],
            default => [
                'style' => 'Följ mallens standard men anpassa efter publik och mål.',
                'cta'   => 'Tydlig CTA.',
                'length'=> 'Kompakt och tydligt.',
            ],
        };
    }

    public function render(CurrentCustomer $current): View
    {
        $templates = ContentTemplate::orderBy('name')->get();
        $sites = $current->get()?->sites()->orderBy('name')->get() ?? collect();
        $selectedTemplate = $templates->firstWhere('id', $this->template_id);

        return view('livewire.a-i.compose', compact('templates','sites','selectedTemplate'));
    }
}
