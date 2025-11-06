<?php

namespace App\Jobs;

use App\Models\BulkGeneration;
use App\Models\AiContent;
use App\Models\ContentTemplate;
use App\Services\Billing\QuotaGuard;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcessBulkGenerationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public int $bulkGenerationId)
    {
        $this->onQueue('ai');
    }

    public function handle(QuotaGuard $quotaGuard): void
    {
        $bulk = BulkGeneration::with('customer', 'site')->findOrFail($this->bulkGenerationId);

        if ($bulk->status !== 'pending') {
            Log::info("[BulkGen] Hoppar över - status är {$bulk->status}", ['bulk_id' => $bulk->id]);
            return;
        }

        $bulk->update(['status' => 'processing']);

        $variables = $bulk->variables ?? [];
        $totalCount = count($variables);

        if ($totalCount === 0) {
            $bulk->update(['status' => 'failed', 'total_count' => 0]);
            return;
        }

        // Calculate total cost
        $costPerText = $bulk->tone === 'short' ? 10 : 50;
        $totalCost = $totalCount * $costPerText;

        // Check credits before starting
        try {
            $quotaGuard->checkCreditsOrFail($bulk->customer, $totalCost, 'credits');
        } catch (\Throwable $e) {
            $bulk->update([
                'status' => 'failed',
                'total_count' => $totalCount,
            ]);
            Log::error("[BulkGen] Otillräckliga krediter", [
                'bulk_id' => $bulk->id,
                'required' => $totalCost,
                'error' => $e->getMessage(),
            ]);
            return;
        }

        // Check plan limits
        $maxTexts = $this->getMaxTextsForPlan($bulk->customer);
        if ($totalCount > $maxTexts) {
            $bulk->update([
                'status' => 'failed',
                'total_count' => $totalCount,
            ]);
            Log::error("[BulkGen] För många texter för planen", [
                'bulk_id' => $bulk->id,
                'requested' => $totalCount,
                'max_allowed' => $maxTexts,
            ]);
            return;
        }

        $bulk->update(['total_count' => $totalCount]);

        // Find appropriate template
        $template = $this->getTemplateForType($bulk->content_type);
        if (!$template) {
            $bulk->update(['status' => 'failed']);
            Log::error("[BulkGen] Template inte hittat", ['type' => $bulk->content_type]);
            return;
        }

        // Create AI content entries and dispatch generation jobs
        foreach ($variables as $index => $varSet) {
            if (!empty($bulk->custom_title_template)) {
                $titlePlaceholder = $this->replacePlaceholders($bulk->custom_title_template, $varSet);

                // Limit title length
                if (mb_strlen($titlePlaceholder) > 255) {
                    $titlePlaceholder = mb_substr($titlePlaceholder, 0, 252) . '...';
                }
            } else {
                $titlePlaceholder = 'Genererar...';
            }

            $inputs = [
                'channel' => $this->getChannelFromType($bulk->content_type),
                'language' => $bulk->site?->locale ?? 'sv_SE',
                'bulk_template' => $bulk->template_text,
                'bulk_variables' => $varSet,
                'generate_title' => empty($bulk->custom_title_template),
                'custom_title' => !empty($bulk->custom_title_template) ? $titlePlaceholder : null,
            ];

            $content = AiContent::create([
                'customer_id' => $bulk->customer_id,
                'site_id' => $bulk->site_id,
                'template_id' => $template->id,
                'bulk_generation_id' => $bulk->id,
                'title' => $titlePlaceholder,
                'tone' => $bulk->tone,
                'type' => $bulk->content_type,
                'status' => 'queued',
                'placeholders' => $varSet,
                'batch_index' => $index,
                'inputs' => $inputs,
            ]);

            // Dispatch generation job
            dispatch(new GenerateContentJob($content->id))->onQueue('ai');

            Log::info("[BulkGen] Text köad", [
                'bulk_id' => $bulk->id,
                'content_id' => $content->id,
                'index' => $index,
                'title' => $titlePlaceholder,
            ]);
        }

        Log::info("[BulkGen] Alla texter köade", [
            'bulk_id' => $bulk->id,
            'total' => $totalCount,
        ]);
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

    private function generateTitlePlaceholder(string $type, array $variables): string
    {
        // Create a short identifier from first 2 variables
        $parts = array_slice(array_values($variables), 0, 2);
        $identifier = implode(' - ', $parts);

        $prefix = match ($type) {
            'blog' => 'Artikel',
            'social' => 'Inlägg',
            'newsletter' => 'Nyhetsbrev',
            default => 'Text',
        };

        $title = "{$prefix}: {$identifier}";

        // Limit length
        if (mb_strlen($title) > 100) {
            $title = mb_substr($title, 0, 97) . '...';
        }

        return $title;
    }

    private function getTemplateForType(string $type): ?ContentTemplate
    {
        $slug = match ($type) {
            'social' => 'social-facebook',
            'blog' => 'blog',
            'newsletter' => 'newsletter',
            'multi' => 'social-facebook',
            default => 'social-facebook',
        };

        return ContentTemplate::where('slug', $slug)->first();
    }

    private function getChannelFromType(string $type): string
    {
        return match ($type) {
            'social' => 'facebook',
            'blog' => 'blog',
            'newsletter' => 'campaign',
            'multi' => 'auto',
            default => 'auto',
        };
    }

    private function getMaxTextsForPlan($customer): int
    {
        $planService = app(\App\Services\Billing\PlanService::class);
        $plan = $customer->subscription?->plan;

        if (!$plan) {
            return 10; // Default för free/trial
        }

        return match ($plan->slug) {
            'starter' => 10,
            'growth' => 100,
            'pro', 'enterprise' => 200,
            default => 10,
        };
    }
}
