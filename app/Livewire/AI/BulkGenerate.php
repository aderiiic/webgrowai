<?php

namespace App\Livewire\AI;

use App\Jobs\ProcessBulkGenerationJob;
use App\Models\BulkGeneration;
use App\Services\Billing\FeatureGuard;
use App\Support\CurrentCustomer;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.app')]
class BulkGenerate extends Component
{
    use WithFileUploads;

    public string $template_text = '';
    public string $custom_title_template = '';
    public bool $use_custom_title = false;

    public string $variables_input = '';
    public string $content_type = 'social';
    public string $tone = 'short';
    public ?int $site_id = null;

    public array $parsedVariables = [];
    public ?string $previewText = null;
    public ?string $previewTitle = null;
    public int $estimatedCount = 0;
    public int $estimatedCost = 0;

    public function mount(CurrentCustomer $current): void
    {
        $customer = $current->get();
        abort_unless($customer, 403);

        // Kontrollera feature-access och redirecta om nekad
        if (!app(FeatureGuard::class)->canUseFeature($customer, FeatureGuard::FEATURE_BULK_GENERATE)) {
            $this->redirect(route('feature.locked', ['feature' => FeatureGuard::FEATURE_BULK_GENERATE]), navigate: false);
            return;
        }

        // Set default site
        $activeSiteId = $current->getSiteId() ?? session('active_site_id');
        if ($activeSiteId) {
            $this->site_id = (int) $activeSiteId;
        } else {
            $this->site_id = $current->get()?->sites()->orderBy('name')->value('id');
        }

        // Example template
        $this->template_text = 'Besök vår butik i {{stad}} för att upptäcka {{produkt}}!';
    }

    #[On('active-site-updated')]
    public function onActiveSiteUpdated(?int $siteId): void
    {
        $this->site_id = $siteId;
    }

    public function updatedVariablesInput(): void
    {
        $this->parseVariables();
    }

    public function updatedTemplateText(): void
    {
        $this->parseVariables();
    }

    public function updatedCustomTitleTemplate(): void
    {
        $this->parseVariables();
    }

    public function updatedUseCustomTitle(): void
    {
        $this->parseVariables();
    }

    public function updatedTone(): void
    {
        $this->calculateEstimates();
    }

    private function parseVariables(): void
    {
        $this->parsedVariables = [];
        $this->previewText = null;
        $this->estimatedCount = 0;

        if (empty($this->variables_input)) {
            return;
        }

        // Try to parse as CSV (tab or comma separated)
        $lines = array_filter(array_map('trim', explode("\n", $this->variables_input)));

        if (empty($lines)) {
            return;
        }

        // First line is headers
        $headers = $this->parseCsvLine($lines[0]);

        if (empty($headers)) {
            return;
        }

        // Parse data rows
        for ($i = 1; $i < count($lines); $i++) {
            $values = $this->parseCsvLine($lines[$i]);

            if (count($values) !== count($headers)) {
                continue; // Skip malformed rows
            }

            $row = [];
            foreach ($headers as $index => $header) {
                $row[$header] = $values[$index] ?? '';
            }

            $this->parsedVariables[] = $row;
        }

        $this->estimatedCount = count($this->parsedVariables);
        $this->calculateEstimates();

        // Generate preview with first row
        if (!empty($this->parsedVariables) && !empty($this->template_text)) {
            $this->previewText = $this->replacePlaceholders($this->template_text, $this->parsedVariables[0]);

            // Generate title preview
            if ($this->use_custom_title && !empty($this->custom_title_template)) {
                $this->previewTitle = $this->replacePlaceholders($this->custom_title_template, $this->parsedVariables[0]);
            } else {
                $this->previewTitle = null;
            }
        }
    }

    private function parseCsvLine(string $line): array
    {
        // Try tab first, then comma
        if (str_contains($line, "\t")) {
            return array_map('trim', explode("\t", $line));
        }

        return array_map('trim', str_getcsv($line));
    }

    private function replacePlaceholders(string $template, array $variables): string
    {
        $result = $template;

        foreach ($variables as $key => $value) {
            $placeholder = '{{' . $key . '}}';
            $result = str_replace($placeholder, $value, $result);
        }

        return $result;
    }

    private function calculateEstimates(): void
    {
        $costPerText = $this->tone === 'short' ? 10 : 50;
        $this->estimatedCost = $this->estimatedCount * $costPerText;
    }

    public function submit(CurrentCustomer $current)
    {
        $this->validate([
            'template_text' => 'required|string|min:10',
            'custom_title_template' => $this->use_custom_title ? 'required|string|min:3|max:255' : 'nullable|string|max:255',
            'variables_input' => 'required|string|min:3',
            'content_type' => 'required|in:social,blog,newsletter,multi',
            'tone' => 'required|in:short,long',
            'site_id' => 'nullable|exists:sites,id',
        ]);

        $customer = $current->get();
        abort_unless($customer, 403);

        // Parse variables one more time
        $this->parseVariables();

        if (empty($this->parsedVariables)) {
            $this->addError('variables_input', 'Kunde inte tolka variablerna. Kontrollera formatet.');
            return;
        }

        // Check plan limits
        $maxTexts = $this->getMaxTextsForPlan($customer);
        if ($this->estimatedCount > $maxTexts) {
            $this->addError('general', "Din plan tillåter max {$maxTexts} texter per batch. Du försöker generera {$this->estimatedCount}.");
            return;
        }

        // Check credits
        try {
            app(\App\Services\Billing\QuotaGuard::class)->checkCreditsOrFail($customer, $this->estimatedCost, 'credits');
        } catch (\Throwable $e) {
            $this->addError('general', $e->getMessage());
            return;
        }

        // Create bulk generation
        $bulk = BulkGeneration::create([
            'customer_id' => $customer->id,
            'site_id' => $this->site_id,
            'template_text' => $this->template_text,
            'custom_title_template' => $this->use_custom_title ? $this->custom_title_template : null,
            'variables' => $this->parsedVariables,
            'status' => 'pending',
            'total_count' => $this->estimatedCount,
            'content_type' => $this->content_type,
            'tone' => $this->tone,
        ]);

        // Dispatch job to process
        dispatch(new ProcessBulkGenerationJob($bulk->id))->onQueue('ai');

        return redirect()->route('ai.bulk.detail', $bulk->id)
            ->with('success', "Massgenerering startad! {$this->estimatedCount} texter kommer att skapas.");
    }

    private function getMaxTextsForPlan($customer): int
    {
        $featureGuard = app(FeatureGuard::class);

        // Hämta limit från plan_features
        $limit = $featureGuard->getFeatureLimit($customer, FeatureGuard::FEATURE_BULK_LIMIT);

        // Fallback om inte konfigurerat
        if ($limit === null) {
            $plan = $customer->subscription?->plan;

            return match ($plan?->slug ?? '') {
                'growth' => 25,
                'pro', 'enterprise' => 50,
                default => 10,
            };
        }

        return $limit;
    }

    public function render(CurrentCustomer $current): View
    {
        $sites = $current->get()?->sites()->orderBy('name')->get() ?? collect();
        $customer = $current->get();
        $maxTexts = $customer ? $this->getMaxTextsForPlan($customer) : 10;

        return view('livewire.a-i.bulk-generate', compact('sites', 'maxTexts'));
    }
}
