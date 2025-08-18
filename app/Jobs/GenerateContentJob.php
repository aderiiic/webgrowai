<?php

namespace App\Jobs;

use App\Models\AiContent;
use App\Services\AI\AiProviderManager;
use App\Support\Usage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class GenerateContentJob implements ShouldQueue
{
    use Queueable;

    public function __construct(public int $aiContentId)
    {
        $this->onQueue('ai');
    }

    public function handle(AiProviderManager $manager, Usage $usage): void
    {
        $content = AiContent::with('template')->findOrFail($this->aiContentId);

        $vars = [
            'title'    => $content->title,
            'audience' => $content->inputs['audience'] ?? null,
            'goal'     => $content->inputs['goal'] ?? null,
            'keywords' => $content->inputs['keywords'] ?? [],
            'brand'    => $content->inputs['brand'] ?? [],
        ];

        $templateHint = view('prompts.' . $content->template->slug, $vars)->render();

        $channel    = (string) ($content->inputs['channel'] ?? 'auto');
        $guidelines = (array) ($content->inputs['guidelines'] ?? []);
        $brandVoice = (string) ($vars['brand']['voice'] ?? '');
        $tone       = (string) $content->tone;

        $gStyle  = $guidelines['style']  ?? null;
        $gCta    = $guidelines['cta']    ?? null;
        $gLength = $guidelines['length'] ?? null;

        $channelRules = match ($channel) {
            'facebook'  => "- 0–3 hashtags. Max 1–2 emojis. 1–2 korta stycken.\n- Nämn inte LinkedIn.",
            'instagram' => "- Hashtags i slutet: 5–10 relevanta. Radbrytningar för läsbarhet.\n- Nämn inte LinkedIn.",
            'linkedin'  => "- Professionell och saklig. 1–3 hashtags. Ingen clickbait.",
            'blog'      => "- Använd H2/H3. Gärna punktlistor. Längre format.",
            'campaign'  => "- Lista idéer kort och konkret (punktlista).",
            default     => "- Följ mallen, anpassa till målgrupp/mål.",
        };

        $toneRule = $tone === 'short'
            ? "Prioritera kortfattat format. Klipp onödigt fluff."
            : "Tillåt längre resonemang där det passar, men håll det koncist och värdedrivet.";

        $hardRules = implode("\n", [
            "- Skriv på svenska.",
            "- Inget försnack (inga 'Självklart!' etc.).",
            "- Inga kodblock (inga ```).",
            "- Leverera exakt 1 komplett version.",
        ]);

        $context = [];
        $context[] = "Titel/ämne: " . ($content->title ?: '-');
        if (!empty($vars['audience'])) $context[] = "Målgrupp: {$vars['audience']}";
        if (!empty($vars['goal']))     $context[] = "Affärsmål: {$vars['goal']}";
        if (!empty($vars['keywords'])) $context[] = "Nyckelord: " . implode(', ', (array) $vars['keywords']);
        if (!empty($brandVoice))       $context[] = "Varumärkesröst: {$brandVoice}";

        $guides = [];
        if ($gStyle)  $guides[] = "Stil: {$gStyle}";
        if ($gCta)    $guides[] = "CTA: {$gCta}";
        if ($gLength) $guides[] = "Längd: {$gLength}";

        $finalPrompt = trim("
Du är copywriter. Skapa innehåll anpassat för kanalen: {$channel}.

KONTEXT:
- " . implode("\n- ", $context) . "

RIKTLINJER:
" . (!empty($guides) ? "- " . implode("\n- ", $guides) : "- Använd sund bästa praxis för kanalen") . "

KANALSPECIFIKA REGLER:
{$channelRules}

TON:
{$toneRule}

HÅRDA REGLER:
{$hardRules}

LEVERANSFORMAT:
- Skriv ren text (Markdown endast i bloggläge för rubriker/listor).
- Exakt 1 version (ingen variant-separator).

INNEHÅLLSMALL (HINT – FÖLJ REGLERNA OVAN FÖRST):
{$templateHint}
        ");

        $provider = $manager->choose($content->template, $tone);

        try {
            $output = $provider->generate($finalPrompt, [
                'temperature' => (float) ($content->template->temperature ?? 0.7),
                'max_tokens'  => (int) ($content->template->max_tokens ?? 1500),
            ]);

            $output = trim($output);
            if (str_starts_with($output, '```')) {
                $output = preg_replace('/^```[a-zA-Z0-9_-]*\n?/','', $output);
                $output = preg_replace("/\n?```$/", '', $output);
                $output = trim((string) $output);
            }

            $content->update([
                'provider' => $provider->name(),
                'body_md'  => $output,
                'status'   => 'ready',
                'error'    => null,
            ]);

            $usage->increment($content->customer_id, 'ai.generate');
        } catch (\Throwable $e) {
            $content->update([
                'status' => 'failed',
                'error'  => $e->getMessage(),
            ]);
            throw $e;
        }
    }
}
