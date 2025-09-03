<?php

namespace App\Jobs;

use App\Models\AiContent;
use App\Models\Site;
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

        $bizContext = '';
        if (!empty($content->site_id)) {
            $site = Site::find($content->site_id);
            if ($site) {
                $summary = trim($site->aiContextSummary());
                if ($summary !== '') {
                    $bizContext = "KONCERN-/VERKSAMHETSKONTEXT: {$summary}\n";
                }
            }
        }

        $gStyle  = $guidelines['style']  ?? null;
        $gCta    = $guidelines['cta']    ?? null;
        $gLength = $guidelines['length'] ?? null;

        $channelRules = match ($channel) {
            'facebook'  => "- 0–3 hashtags. Max 1–2 emojis. 1–2 korta stycken.\n- Nämn inte LinkedIn eller andra konkurrenter.",
            'instagram' => "- Hashtags i slutet: 5–10 relevanta. Radbrytningar för läsbarhet.\n- Nämn inte LinkedIn eller andra konkurrenter.",
            'linkedin'  => "- Professionell och saklig. 1–3 hashtags. Ingen clickbait.\n- Nämn inte Facebook, Instagram eller andra konkurrenter.",
            'blog'      => "- Börja med några inledningsstycken (vanlig text) som introducerar ämnet.\n- Använd sedan H2/H3 för underrubriker. Gärna punktlistor.\n- VIKTIGT: Inkludera INTE titeln som H1 i innehållet - titeln hanteras separat.\n- Nämn inte konkurrenter eller externa webbplatser.",
            'campaign'  => "- Lista idéer kort och konkret (punktlista).\n- Nämn inte konkurrenter eller externa webbplatser.",
            default     => "- Följ mallen, anpassa till målgrupp/mål.\n- Nämn inte konkurrenter eller externa webbplatser.",
        };

        // Förbättrad längdhantering med tydligare skillnader
        $toneRule = $tone === 'short'
            ? $this->getShortToneRules($channel)
            : $this->getLongToneRules($channel);

        $competitorProtection = "";
        if ($content->site_id) {
            $site = \App\Models\Site::find($content->site_id);
            if ($site && $site->domain) {
                $domain = parse_url($site->domain, PHP_URL_HOST) ?? $site->domain;
                $competitorProtection = "- Nämn ENDAST domänen {$domain} om du hänvisar till webbsidor.\n- Inkludera ALDRIG andra företags domäner, webbsidor eller konkurrenters länkar.\n";
            }
        }

        $hardRules = implode("\n", [
            "- Skriv på svenska.",
            "- Inget försnack (inga 'Självklart!' etc.).",
            "- Inga kodblock (inga ```).",
            "- Leverera exakt 1 komplett version.",
            "- För blog/WordPress: Börja INTE med titeln som H1 - den sätts automatiskt.",
            "- För blog/WordPress: Börja med vanlig text (inledning), sedan använd H2/H3 för rubriker.",
            "- Inkludera INGA bildreferenser i texten - bilder hanteras separat.",
            $competitorProtection, // Lägg till säkerhetsregeln här
            "- Fokusera på allmänna tips och bästa praxis istället för specifika företag/domäner.",
        ]);

        $context = [];
        if ($bizContext !== '') {
            $context[] = rtrim($bizContext);
        }
        $context[] = "Titel/ämne: " . ($content->title ?: '-');
        if (!empty($vars['audience'])) $context[] = "Målgrupp: {$vars['audience']}";
        if (!empty($vars['goal']))     $context[] = "Affärsmål: {$vars['goal']}";
        if (!empty($vars['keywords'])) $context[] = "Nyckelord: " . implode(', ', (array) $vars['keywords']);
        if (!empty($brandVoice))       $context[] = "Varumärkesröst: {$brandVoice}";

        $guides = [];
        if ($gStyle)  $guides[] = "Stil: {$gStyle}";
        if ($gCta)    $guides[] = "CTA: {$gCta}";
        if ($gLength) $guides[] = "Längd: {$gLength}";

        // Särskild instruktion för blog-format
        $blogStructure = '';
        if ($channel === 'blog') {
            $blogStructure = "\n\nSTRUKTUR FÖR BLOG:\n1. Börja med några inledande stycken (vanlig text utan rubriker)\n2. Fortsätt med H2-rubriker för huvudavsnitt\n3. Använd H3 för underavsnitt\n4. Avsluta med en slutsats eller sammanfattning\n\nExempel:\n[Inledningstext som förklarar ämnet...]\n\n[Mer inledande text som sätter upp problemet...]\n\n## Första huvudrubriken\n[Innehåll...]\n\n## Andra huvudrubriken\n[Innehåll...]\n";
        }

        $finalPrompt = trim("
Du är copywriter. Skapa innehåll anpassat för kanalen: {$channel}.

KONTEXT:
- " . implode("\n- ", $context) . "

RIKTLINJER:
" . (!empty($guides) ? "- " . implode("\n- ", $guides) : "- Använd sund bästa praxis för kanalen") . "

KANALSPECIFIKA REGLER:
{$channelRules}

LÄNGD OCH TON:
{$toneRule}

HÅRDA REGLER:
{$hardRules}
{$blogStructure}
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

    private function getShortToneRules(string $channel): string
    {
        return match ($channel) {
            'facebook' => "SNABB IMPACT: 50–80 ord. Börja med en fångande första mening (fråga, statistik eller starkt påstående). 1–2 korta stycken. Var direkt, engagerande och avsluta gärna med en fråga eller CTA. Använd emojis för känsla och engagemang.",
            'instagram' => "SNABB IMPACT: 80–120 ord. 2–3 korta stycken med tydliga radbrytningar. Starta med en emotionell hook, förstärk med emojis där det passar. Fokusera på känsla, inspiration och visuell rytm. Avsluta med CTA (t.ex. 'Spara', 'Tagga en vän').",
            'linkedin' => "SNABB IMPACT: 100–150 ord. 1–2 stycken. Börja med en fångande första mening som väcker intresse (statistik, fråga eller insikt). Förstärk med känsla och emojis men överdriv inte. Professionell men personlig ton, dela värde och erfarenhet. Avsluta med en fråga eller CTA för dialog.",
            'blog' => "SNABB IMPACT: 300–500 ord. Max 1–2 H2-rubriker. Starta med ett värdeförslag i första meningen. Gå snabbt till poängen, använd konkreta exempel, avsluta med tydlig CTA.",
            'campaign' => "SNABB IMPACT: 3 idéer, max 2–3 meningar per idé. Börja varje idé med en hook. Gör idéerna unika, konkreta och avsluta med tydlig CTA.",
            default => "SNABB IMPACT: Håll det mycket koncist – ungefär halva normal-längden för kanalen. Börja alltid med en hook och avsluta med CTA.",
        };
    }

    private function getLongToneRules(string $channel): string
    {
        return match ($channel) {
            'facebook' => "DJUP IMPACT: 150–250 ord. 3–4 stycken. Berätta en engagerande historia, börja med hook, förstärk med känsla och emojis. Bygg upp mot en tydlig CTA i slutet.",
            'instagram' => "DJUP IMPACT: 300–500 ord. 4–6 stycken med tydliga radbrytningar. Satsa på storytelling, emotion och visuellt flöde. Använd emojis strategiskt för att förstärka poänger. Avsluta med CTA (t.ex. 'Spara', 'Tagga en vän').",
            'linkedin' => "DJUP IMPACT: 400–800 ord. 4–6 stycken. Börja med en stark hook (statistik, fråga eller påstående). Förstärk med känsla och emojis men överdriv inte. Utveckla insikter och lärdomar professionellt men personligt. Avsluta med en fråga eller CTA för engagemang och diskussion.",
            'blog' => "DJUP IMPACT: 800–1500 ord. 4–8 H2-rubriker. Börja med ett värdeförslag i första stycket. Bygg upp ämnet logiskt med exempel och detaljer, inkludera storytelling. Avrunda med tydlig CTA (kontakt, nedladdning, köp).",
            'campaign' => "DJUP IMPACT: 5–8 koncept. Börja varje koncept med en hook, beskriv värde och nytta detaljerat, avsluta med tydlig call-to-action. Använd gärna visuella inslag eller emojis för att förstärka idéerna.",
            default => "DJUP IMPACT: Utveckla ämnet grundligt med storytelling, konkreta exempel och tydliga CTA. Börja alltid med hook för att fånga uppmärksamhet.",
        };
    }
}
