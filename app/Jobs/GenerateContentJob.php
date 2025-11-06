<?php

namespace App\Jobs;

use App\Models\AiContent;
use App\Models\Customer;
use App\Models\Site;
use App\Services\AI\AiProviderManager;
use App\Services\Billing\QuotaGuard;
use App\Support\Usage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;
use Throwable;

/**
 * Job for generating AI content based on templates and user inputs.
 *
 * Handles content generation for various channels (blog, social media, SEO, etc.)
 * with support for multiple languages, tones, and custom guidelines.
 */
class GenerateContentJob implements ShouldQueue
{
    use Queueable;

    // Cost constants
    private const COST_SHORT = 10;
    private const COST_LONG = 50;

    // Supported languages
    private const SUPPORTED_LANGUAGES = ['sv', 'en', 'de'];
    private const DEFAULT_LANGUAGE = 'sv';

    // Channel types
    private const CHANNEL_FACEBOOK = 'facebook';
    private const CHANNEL_INSTAGRAM = 'instagram';
    private const CHANNEL_LINKEDIN = 'linkedin';
    private const CHANNEL_BLOG = 'blog';
    private const CHANNEL_CAMPAIGN = 'campaign';
    private const CHANNEL_SEO = 'seo';
    private const CHANNEL_PRODUCT = 'product';

    // Blog length presets
    private const BLOG_LENGTH_SHORT = 'short';
    private const BLOG_LENGTH_LONG = 'long';
    private const BLOG_CHAR_TARGET = [
        self::BLOG_LENGTH_SHORT => '≈ 4 000–5 000 tecken',
        self::BLOG_LENGTH_LONG => '≈ 8 000–12 000 tecken',
        'default' => '≈ 5 000–8 000 tecken',
    ];

    // Token limits
    private const TOKEN_BASE_MAX = 1500;
    private const TOKEN_SOCIAL_SHORT = 700;
    private const TOKEN_SOCIAL_LONG = 1200;
    private const TOKEN_BLOG = [
        self::BLOG_LENGTH_SHORT => 2000,
        self::BLOG_LENGTH_LONG => 4000,
        'default' => 3000,
    ];

    public function __construct(public int $aiContentId)
    {
        $this->onQueue('ai');
    }

    /**
     * Execute the job.
     */
    public function handle(
        AiProviderManager $manager,
        Usage $usage,
        QuotaGuard $quotaGuard
    ): void {
        $content = AiContent::with(['template', 'customer', 'site'])->findOrFail($this->aiContentId);

        try {
            $this->generateContent($content, $manager, $usage, $quotaGuard);
        } catch (Throwable $e) {
            $this->handleFailure($content, $e);
            throw $e;
        }
    }

    /**
     * Main content generation flow.
     */
    private function generateContent(
        AiContent $content,
        AiProviderManager $manager,
        Usage $usage,
        QuotaGuard $quotaGuard
    ): void {
        // Extract and prepare inputs
        $inputs = $this->extractInputs($content);
        $context = $this->buildBusinessContext($content);

        // Build the final prompt
        $finalPrompt = $this->buildPrompt($content, $inputs, $context);

        // Choose provider and check quota
        $provider = $manager->choose($content->template, $inputs['tone']);
        $cost = $this->calculateCost($inputs['tone']);

        if ($content->customer) {
            $quotaGuard->checkCreditsOrFail($content->customer, $cost, 'credits');
        }

        // Generate content via AI
        $maxTokens = $this->calculateMaxTokens($content, $inputs);
        $temperature = $this->getTemperature($content, $inputs['channel']);

        $output = $provider->generate($finalPrompt, [
            'temperature' => $temperature,
            'max_tokens' => $maxTokens,
        ]);

        // Post-process output
        $output = $this->cleanOutput($output);

        $generatedTitle = null;
        if ($inputs['generateTitle']) {
            $extracted = $this->extractAndCleanTitle($output);

            if ($extracted['title']) {
                // Title was in the output, use it
                $generatedTitle = $extracted['title'];
                $output = $extracted['body'];

                Log::info("[BulkGen] Extracted title from body", [
                    'content_id' => $content->id,
                    'title' => $generatedTitle,
                ]);
            } else {
                // No title in output, generate one separately
                $generatedTitle = $this->generateSeoTitle($content, $inputs, $provider, $output);
            }
        }

        $output = $this->ensureLinkPresence($output, $inputs['linkUrl'], $inputs['channel']);

        // Save results
        $content->update([
            'provider' => $provider->name(),
            'body_md' => $output,
            'title' => $generatedTitle ?? $content->title,
            'status' => 'ready',
            'error' => null,
        ]);

        // Track usage and charge
        $usage->increment($content->customer_id, 'ai.generate');

        if ($content->customer) {
            $quotaGuard->chargeCredits($content->customer, $cost, 'credits');
        }

        // Generate image if requested
        $this->dispatchImageGenerationIfNeeded($content, $inputs);
    }

    /**
     * Extract and normalize all inputs from content.
     */
    private function extractInputs(AiContent $content): array
    {
        $inputs = $content->inputs ?? [];

        return [
            // Basic inputs
            'title' => $content->title,
            'audience' => $inputs['audience'] ?? null,
            'goal' => $inputs['goal'] ?? null,
            'keywords' => $inputs['keywords'] ?? [],
            'brand' => $inputs['brand'] ?? [],
            'tone' => (string) $content->tone,

            // Channel and guidelines
            'channel' => (string) ($inputs['channel'] ?? 'auto'),
            'guidelines' => (array) ($inputs['guidelines'] ?? []),
            'brandVoice' => (string) ($inputs['brand']['voice'] ?? ''),

            // URLs
            'linkUrl' => trim((string) ($inputs['link_url'] ?? '')) ?: null,
            'sourceUrl' => trim((string) ($inputs['source_url'] ?? '')) ?: null,

            // Social settings
            'socialSettings' => (array) ($inputs['social_settings'] ?? []),
            'includeHashtags' => (bool) ($inputs['social_settings']['include_hashtags'] ?? false),
            'lengthChars' => trim((string) ($inputs['social_settings']['length_chars'] ?? '')),

            // Image settings
            'imageInput' => (array) ($inputs['image'] ?? []),
            'shouldGenImg' => (bool) ($inputs['image']['generate'] ?? false),
            'imageMode' => (string) ($inputs['image']['mode'] ?? 'auto'),
            'imagePrompt' => (string) ($inputs['image']['prompt'] ?? ''),

            // Blog settings
            'blogSettings' => (array) ($inputs['blog_settings'] ?? []),
            'blogWordLength' => (string) ($inputs['blog_settings']['word_length'] ?? ''),

            // SEO settings
            'seoOptimize' => (array) ($inputs['seo_optimize'] ?? []),

            // Language
            'language' => $this->determineLanguage($inputs),

            'bulkTemplate' => $inputs['bulk_template'] ?? null,
            'bulkVariables' => $inputs['bulk_variables'] ?? [],
            'generateTitle' => (bool) ($inputs['generate_title'] ?? false),
            'customTitle' => $inputs['custom_title'] ?? null,
        ];
    }

    /**
     * Determine the language to use for content generation.
     */
    private function determineLanguage(array $inputs): string
    {
        $langInput = $inputs['language'] ?? $inputs['locale'] ?? null;

        if (!$langInput) {
            return self::DEFAULT_LANGUAGE;
        }

        $langCode = strtolower((string) $langInput);

        // Extract language code from locale (sv_SE -> sv)
        if (str_contains($langCode, '_') || str_contains($langCode, '-')) {
            $langCode = substr($langCode, 0, 2);
        }

        $language = in_array($langCode, self::SUPPORTED_LANGUAGES, true)
            ? $langCode
            : self::DEFAULT_LANGUAGE;

        Log::info("Language determined", ['input' => $langInput, 'output' => $language]);

        return $language;
    }

    /**
     * Build business context from site information.
     */
    private function buildBusinessContext(AiContent $content): string
    {
        if (empty($content->site_id) || !$content->site) {
            return '';
        }

        $summary = trim($content->site->aiContextSummary());

        if ($summary === '') {
            return '';
        }

        return "FÖRETAGSINFORMATION (använd denna i texten, ersätt ALDRIG med generiska platshållare som [Ditt Företagsnamn]):\n{$summary}\n";
    }

    /**
     * Build the complete AI prompt.
     */
    private function buildPrompt(AiContent $content, array $inputs, string $bizContext): string
    {
        $language = $inputs['language'];

        if ($inputs['generateTitle'] && !empty($inputs['bulkTemplate'])) {
            $templateHint = $this->buildBulkPrompt($inputs, $language);
        } else {
            // Render standard template hint
            $templateHint = view('prompts.' . $content->template->slug, [
                'title' => $inputs['title'],
                'audience' => $inputs['audience'],
                'goal' => $inputs['goal'],
                'keywords' => $inputs['keywords'],
                'brand' => $inputs['brand'],
            ])->render();
        }

        // Build context array
        $context = $this->buildContextArray($inputs, $bizContext, $language);

        // Build guidelines
        $guides = $this->buildGuidelinesList($inputs, $language);

        // Get channel rules
        $channelRules = $this->getChannelRules($inputs, $language);

        // Get tone rules
        $toneRule = $this->getToneRules($inputs['tone'], $inputs['channel']);

        // Build hard rules
        $hardRules = $this->buildHardRules($content, $inputs, $language);

        // Get blog structure note if applicable
        $blogStructure = $this->getBlogStructureNote($inputs, $language);

        // Get labels based on language
        $labels = $this->getPromptLabels($language);

        // Assemble final prompt
        return trim("
{$labels['role']}

{$labels['context']}:
- " . implode("\n- ", $context) . "

{$labels['guidelines']}:
" . (!empty($guides) ? "- " . implode("\n- ", $guides) : $labels['bestPractice']) . "

{$labels['channel']}:
{$channelRules}

{$labels['lengthTone']}:
{$toneRule}

{$labels['hard']}:
{$hardRules}
{$blogStructure}
{$labels['delivery']}:
{$labels['deliveryBul1']}
{$labels['deliveryBul2']}

{$labels['template']}
{$templateHint}
        ");
    }

    /**
     * Build prompt for bulk generation
     */
    private function buildBulkPrompt(array $inputs, string $language): string
    {
        $template = $inputs['bulkTemplate'];
        $variables = $inputs['bulkVariables'];

        // Replace variables in template
        $instruction = $template;
        foreach ($variables as $key => $value) {
            $instruction = str_replace('{{' . $key . '}}', $value, $instruction);
        }

        // Extract variable names and values for emphasis - DETTA SAKNADES!
        $variableList = [];
        foreach ($variables as $key => $value) {
            $variableList[] = "{$key}: \"{$value}\"";
        }
        $varsString = implode(', ', $variableList);

        if ($language === 'en') {
            return "YOUR TASK:\n{$instruction}\n\nKEY VARIABLES THAT MUST APPEAR IN THE TEXT:\n{$varsString}\n\nCRITICAL REQUIREMENTS:\n- You MUST use all the provided variables naturally throughout the text\n- The variable values (like '{$varsString}') must appear AT LEAST 3-5 times each distributed throughout the content\n- For local SEO purposes, these exact terms are critical - ensure they appear naturally in different contexts\n- Make it sound natural and engaging, not forced or repetitive\n- DO NOT just mention them once and forget - weave them throughout the entire text\n- Start your response with a catchy, SEO-optimized title on the first line, then leave one blank line, then start the actual content\n- Do NOT include the title in the body text itself";
        }

        if ($language === 'de') {
            return "DEINE AUFGABE:\n{$instruction}\n\nSCHLÜSSELVARIABLEN DIE IM TEXT ERSCHEINEN MÜSSEN:\n{$varsString}\n\nKRITISCHE ANFORDERUNGEN:\n- Du MUSST alle bereitgestellten Variablen natürlich im Text verwenden\n- Die Variablenwerte (wie '{$varsString}') müssen MINDESTENS 3-5 mal jeweils verteilt im Inhalt vorkommen\n- Für lokale SEO-Zwecke sind diese exakten Begriffe kritisch - stelle sicher dass sie natürlich in verschiedenen Kontexten erscheinen\n- Klinge natürlich und ansprechend, nicht forciert oder repetitiv\n- Erwähne sie NICHT nur einmal und vergiss sie - verwebe sie durch den gesamten Text\n- Beginne deine Antwort mit einem eingängigen, SEO-optimierten Titel in der ersten Zeile, dann eine Leerzeile, dann beginne den eigentlichen Inhalt\n- Füge den Titel NICHT in den Fließtext selbst ein";
        }

        return "DITT UPPDRAG:\n{$instruction}\n\nNYCKELVARIABLER SOM MÅSTE FÖREKOMMA I TEXTEN:\n{$varsString}\n\nKRITISKA KRAV:\n- Du MÅSTE använda alla angivna variabler naturligt genom hela texten\n- Variabelvärdena (som '{$varsString}') måste förekomma MINST 3-5 gånger vardera fördelade genom innehållet\n- För lokal SEO är dessa exakta termer kritiska - se till att de förekommer naturligt i olika sammanhang\n- Låt det låta naturligt och engagerande, inte påtvingat eller repetitivt\n- Nämn dem INTE bara en gång och glöm bort - väv in dem genom hela texten\n- Börja ditt svar med en catchy, SEO-optimerad titel på första raden, lämna sedan en blank rad, och börja sedan det faktiska innehållet\n- Inkludera INTE titeln i brödtexten själv";
    }

    /**
     * Extract title from body if present and clean body
     */
    private function extractAndCleanTitle(string $body): array
    {
        $lines = explode("\n", $body);

        // Check if first line looks like a title
        if (count($lines) > 2) {
            $firstLine = trim($lines[0]);
            $secondLine = trim($lines[1] ?? '');

            // If first line is short and second line is empty (our format)
            if ($firstLine !== '' && $secondLine === '' && mb_strlen($firstLine) < 120) {
                // Remove markdown heading if present
                $title = preg_replace('/^#+\s*/', '', $firstLine);

                // Remove first two lines (title + blank line)
                array_shift($lines);
                array_shift($lines);

                $cleanBody = trim(implode("\n", $lines));

                return ['title' => $title, 'body' => $cleanBody];
            }
        }

        return ['title' => null, 'body' => $body];
    }

    /**
     * Build the context array for the prompt.
     */
    private function buildContextArray(array $inputs, string $bizContext, string $language): array
    {
        $context = [];

        if ($bizContext !== '') {
            $context[] = $bizContext;
        }

        if ($inputs['audience']) {
            $label = $language === 'en' ? 'Target audience' : 'Målgrupp';
            $context[] = "{$label}: {$inputs['audience']}";
        }

        if ($inputs['goal']) {
            $label = $language === 'en' ? 'Goal/Purpose' : 'Mål/Syfte';
            $context[] = "{$label}: {$inputs['goal']}";
        }

        if (!empty($inputs['keywords'])) {
            $label = $language === 'en' ? 'Keywords to include naturally' : 'Nyckelord att inkludera naturligt';
            $context[] = "{$label}: " . implode(', ', (array) $inputs['keywords']);
        }

        if ($inputs['brandVoice']) {
            $label = $language === 'en' ? 'Brand voice' : 'Varumärkesröst';
            $context[] = "{$label}: {$inputs['brandVoice']}";
        }

        if ($inputs['sourceUrl']) {
            $label = $language === 'en' ? 'Source material' : 'Källmaterial';
            $context[] = "{$label}: {$inputs['sourceUrl']}";
        }

        return $context;
    }

    /**
     * Build guidelines list.
     */
    private function buildGuidelinesList(array $inputs, string $language): array
    {
        $guides = [];
        $guidelines = $inputs['guidelines'];

        if (!empty($guidelines['style'])) {
            $label = $language === 'en' ? 'Style' : 'Stil';
            $guides[] = "{$label}: {$guidelines['style']}";
        }

        if (!empty($guidelines['cta'])) {
            $label = $language === 'en' ? 'Call-to-Action' : 'Uppmaning';
            $guides[] = "{$label}: {$guidelines['cta']}";
        }

        if (!empty($guidelines['length'])) {
            $label = $language === 'en' ? 'Length guidance' : 'Längdvägledning';
            $guides[] = "{$label}: {$guidelines['length']}";
        }

        return $guides;
    }

    /**
     * Get channel-specific rules.
     */
    private function getChannelRules(array $inputs, string $language): string
    {
        $channel = $inputs['channel'];
        $hashtagRule = $this->getHashtagRule($channel, $inputs['includeHashtags'], $language);

        if ($language === 'en') {
            return $this->getChannelRulesEnglish($channel, $hashtagRule);
        }

        return $this->getChannelRulesSwedish($channel, $hashtagRule);
    }

    /**
     * Get Swedish channel rules.
     */
    private function getChannelRulesSwedish(string $channel, string $hashtagRule): string
    {
        return match ($channel) {
            self::CHANNEL_FACEBOOK => "- Ton: vänlig och konversationell.\n- Emojis: max 0–2 där det stärker budskapet.\n- Struktur: 1–2 mycket korta stycken. Första meningen ska vara en hook.\n{$hashtagRule}\n- Undvik clickbait och vaga löften.",

            self::CHANNEL_INSTAGRAM => "- Ton: berättande och emotionell.\n- Struktur: radbrytningar, 2–4 korta block. Första raden: stark hook.\n{$hashtagRule}\n- Använd emojis strategiskt, inte överdrivet.",

            self::CHANNEL_LINKEDIN => "- Ton: professionell, insiktsfull, personlig utan att bli privat.\n- Struktur: 2–4 kompakta stycken, inled med konkret insikt eller fråga.\n{$hashtagRule}\n- Emojis: undvik eller max 1-2 i hela inlägget. Ska inte låta AI-genererat.",

            self::CHANNEL_BLOG => "- Börja med några inledningsstycken (vanlig text) som introducerar ämnet.\n- Använd sedan H2/H3 för underrubriker. Gärna punktlistor.\n- VIKTIGT: Inkludera INTE titeln som H1 i innehållet - titeln hanteras separat.\n- Nämn inte konkurrenter eller externa webbplatser.",

            self::CHANNEL_CAMPAIGN => "- Lista idéer kort och konkret (punktlista).\n- Nämn inte konkurrenter eller externa webbplatser.",

            self::CHANNEL_SEO => "- UPPDRAG: Förbättra endast språket i ORIGINALTEXTEN nedan (mening för mening). Ändra inte betydelsen.\n- INGA NYA RUBRIKER, INGA LISTOR, INGA EXTRA STYCKEN.\n- Behåll exakt samma styckeindelning och meningsordning som originalet.\n- Gör små förbättringar: tydlighet, flyt, stavning/grammatik, naturlig inkl. av nyckelord (ingen keyword‑stuffing).\n- Ta bort upprepningar/fyllnad om det kan göras utan att ändra betydelsen.\n- INGEN intro/avslut/CTA om det inte finns i originalet.\n- LEVERANSFORMAT: Endast ren brödtext, rad för rad som originalet. Inga Markdown‑rubriker, inga punktlistor.",

            self::CHANNEL_PRODUCT => "- UPPDRAG: Skapa en säljande, tydlig och SEO‑vänlig produktbeskrivning.\n- Struktur: Kort inledning (1–2 meningar) med värde, sedan punktlista med features, separat punktlista med fördelar/USPs, avsluta med CTA (köp/lägg i varukorg/kontakta).\n- Använd H2/H3 för sektioner: t.ex. 'Egenskaper', 'Fördelar', 'Specifikation'.\n- Undvik bloggartikel-ton; inga långa berättelser. Koncist och konkret.\n- Integrera ev. målgrupp/varumärkesröst om angivet.",

            default => "- Följ mallen, anpassa till målgrupp/mål.\n- Nämn inte konkurrenter eller externa webbplatser.",
        };
    }

    /**
     * Get English channel rules.
     */
    private function getChannelRulesEnglish(string $channel, string $hashtagRule): string
    {
        return match ($channel) {
            self::CHANNEL_FACEBOOK => "- Tone: friendly and conversational.\n- Emojis: max 0–2 where it strengthens the message.\n- Structure: 1–2 very short paragraphs. First sentence should be a hook.\n{$hashtagRule}\n- Avoid clickbait and vague promises.",

            self::CHANNEL_INSTAGRAM => "- Tone: narrative and emotional.\n- Structure: line breaks, 2–4 short blocks. First line: strong hook.\n{$hashtagRule}\n- Use emojis strategically, not excessively.",

            self::CHANNEL_LINKEDIN => "- Tone: professional, insightful, personal without being private.\n- Structure: 2–4 compact paragraphs, start with concrete insight or question.\n{$hashtagRule}\n- Emojis: avoid or max 1-2 in the entire post. Should not sound AI-generated.",

            self::CHANNEL_BLOG => "- Start with a few introductory paragraphs (plain text) introducing the topic.\n- Then use H2/H3 for subheadings. Bullet lists are welcome.\n- IMPORTANT: Do NOT include the title as H1 in the content - the title is handled separately.\n- Do not mention competitors or external websites.",

            self::CHANNEL_CAMPAIGN => "- List ideas briefly and concretely (bullet list).\n- Do not mention competitors or external websites.",

            self::CHANNEL_SEO => "- MISSION: Only improve the language in the ORIGINAL TEXT below (sentence by sentence). Do not change the meaning.\n- NO NEW HEADINGS, NO LISTS, NO EXTRA PARAGRAPHS.\n- Keep exactly the same paragraph division and sentence order as the original.\n- Make small improvements: clarity, flow, spelling/grammar, natural inclusion of keywords (no keyword-stuffing).\n- Remove repetitions/filler if it can be done without changing the meaning.\n- NO intro/conclusion/CTA if it doesn't exist in the original.\n- DELIVERY FORMAT: Only plain body text, line by line as the original. No Markdown headings, no bullet lists.",

            self::CHANNEL_PRODUCT => "- MISSION: Create a compelling, clear and SEO-friendly product description.\n- Structure: Short introduction (1–2 sentences) with value, then bullet list with features, separate bullet list with benefits/USPs, end with CTA (buy/add to cart/contact).\n- Use H2/H3 for sections: e.g. 'Features', 'Benefits', 'Specifications'.\n- Avoid blog article tone; no long stories. Concise and concrete.\n- Integrate audience/brand voice if specified.",

            default => "- Follow the template, adapt to audience/goal.\n- Do not mention competitors or external websites.",
        };
    }

    /**
     * Get hashtag rule based on channel.
     */
    private function getHashtagRule(string $channel, bool $includeHashtags, string $language): string
    {
        if (!$includeHashtags) {
            return $language === 'en' ? '- No hashtags.' : '- Inga hashtags.';
        }

        return match ($channel) {
            self::CHANNEL_FACEBOOK => $language === 'en'
                ? '- Hashtags: 0–3 relevant. Place last or integrate sparingly.'
                : '- Hashtags: 0–3 relevanta. Placera sist eller integrera sparsamt.',

            self::CHANNEL_INSTAGRAM => $language === 'en'
                ? '- Hashtags: 5–10 relevant. ALWAYS place in a separate last block line (not in body text).'
                : '- Hashtags: 5–10 relevanta. Placera ALLTID i en separat sista blockrad (inte i brödtexten).',

            self::CHANNEL_LINKEDIN => $language === 'en'
                ? '- Hashtags: 1–3 professional. Place last.'
                : '- Hashtags: 1–3 professionella. Placera sist.',

            default => '',
        };
    }

    /**
     * Get tone rules based on tone type and channel.
     */
    private function getToneRules(string $tone, string $channel): string
    {
        return $tone === 'short'
            ? $this->getShortToneRules($channel)
            : $this->getLongToneRules($channel);
    }

    /**
     * Get short tone rules for different channels.
     */
    private function getShortToneRules(string $channel): string
    {
        return match ($channel) {
            self::CHANNEL_FACEBOOK => "SNABB IMPACT: 50–80 ord. Börja med en fångande första mening (fråga, statistik eller starkt påstående). 1–2 korta stycken. Var direkt, engagerande och avsluta gärna med en fråga eller CTA. Använd emojis för känsla och engagemang.",

            self::CHANNEL_INSTAGRAM => "SNABB IMPACT: 80–120 ord. 2–3 korta stycken med tydliga radbrytningar. Starta med en emotionell hook, förstärk med emojis där det passar. Fokusera på känsla, inspiration och visuell rytm. Avsluta med CTA (t.ex. 'Spara', 'Tagga en vän').",

            self::CHANNEL_LINKEDIN => "SNABB IMPACT: 100–150 ord. 1–2 stycken. Börja med en fångande första mening som väcker intresse (statistik, fråga eller insikt). Förstärk med känsla och emojis men överdriv inte. Professionell men personlig ton, dela värde och erfarenhet. Avsluta med en fråga eller CTA för dialog.",

            self::CHANNEL_BLOG => "SNABB IMPACT: 300–500 ord. Max 1–2 H2-rubriker. Starta med ett värdeförslag i första meningen. Gå snabbt till poängen, använd konkreta exempel, avsluta med tydlig CTA.",

            self::CHANNEL_CAMPAIGN => "SNABB IMPACT: 3 idéer, max 2–3 meningar per idé. Börja varje idé med en hook. Gör idéerna unika, konkreta och avsluta med tydlig CTA.",

            default => "SNABB IMPACT: Håll det mycket koncist – ungefär halva normal-längden för kanalen. Börja alltid med en hook och avsluta med CTA.",
        };
    }

    /**
     * Get long tone rules for different channels.
     */
    private function getLongToneRules(string $channel): string
    {
        return match ($channel) {
            self::CHANNEL_FACEBOOK => "DJUP IMPACT: 150–250 ord. 3–4 stycken. Berätta en engagerande historia, börja med hook, förstärk med känsla och emojis. Bygg upp mot en tydlig CTA i slutet.",

            self::CHANNEL_INSTAGRAM => "DJUP IMPACT: 300–500 ord. 4–6 stycken med tydliga radbrytningar. Satsa på storytelling, emotion och visuellt flöde. Använd emojis strategiskt för att förstärka poänger. Avsluta med CTA (t.ex. 'Spara', 'Tagga en vän').",

            self::CHANNEL_LINKEDIN => "DJUP IMPACT: 400–800 ord. 4–6 stycken. Börja med en stark hook (statistik, fråga eller påstående). Förstärk med känsla och emojis men överdriv inte. Utveckla insikter och lärdomar professionellt men personligt. Avsluta med en fråga eller CTA för engagemang och diskussion.",

            self::CHANNEL_BLOG => "DJUP IMPACT: 800–1500 ord. 4–8 H2-rubriker. Börja med ett värdeförslag i första stycket. Bygg upp ämnet logiskt med exempel och detaljer, inkludera storytelling. Avrunda med tydlig CTA (kontakt, nedladdning, köp).",

            self::CHANNEL_CAMPAIGN => "DJUP IMPACT: 5–8 koncept. Börja varje koncept med en hook, beskriv värde och nytta detaljerat, avsluta med tydlig call-to-action. Använd gärna visuella inslag eller emojis för att förstärka idéerna.",

            default => "DJUP IMPACT: Utveckla ämnet grundligt med storytelling, konkreta exempel och tydliga CTA. Börja alltid med hook för att fånga uppmärksamhet.",
        };
    }

    /**
     * Build hard rules section.
     */
    private function buildHardRules(AiContent $content, array $inputs, string $language): string
    {
        /*
        $rules = [];

        // Length enforcement
        $lengthRule = $this->getLengthEnforcementRule($inputs);
        if ($lengthRule) {
            $rules[] = $lengthRule;
        }

        // Competitor protection
        $competitorRule = $this->getCompetitorProtectionRule($content, $inputs['linkUrl']);
        if ($competitorRule) {
            $rules[] = $competitorRule;
        }

        return implode("\n", $rules);
        */

        $siteName = $content->site?->name ?? null;

        $rules = match($language) {
            'en' => [
                "Write in {$language} language.",
                "Generate ONLY the main content body text, no meta-text.",
                "NO titles, headlines or labels at the start - begin directly with content.",
                "DO NOT use generic placeholders like '[Company Name]', '[Your Brand]', '[Product Name]' etc.",
            ],
            'de' => [
                "Schreibe auf {$language}.",
                "Generiere NUR den Hauptinhalt, keine Meta-Texte.",
                "KEINE Titel oder Überschriften am Anfang - beginne direkt mit dem Inhalt.",
                "Verwende KEINE generischen Platzhalter wie '[Firmenname]', '[Ihre Marke]', '[Produktname]' etc.",
            ],
            default => [
                "Skriv på {$language}.",
                "Generera ENDAST själva innehållstexten, ingen metatext.",
                "INGA titlar, rubriker eller etiketter i början - börja direkt med innehållet.",
                "Använd ALDRIG generiska platshållare som '[Företagsnamn]', '[Ditt Företag]', '[Produktnamn]' etc.",
            ],
        };

        // Add specific company name rule if available
        if ($siteName) {
            $rules[] = match($language) {
                'en' => "When referring to the company, always use the actual name: '{$siteName}'",
                'de' => "Verwende bei Firmenbezug immer den tatsächlichen Namen: '{$siteName}'",
                default => "Vid företagsreferens, använd alltid det faktiska namnet: '{$siteName}'",
            };
        } else {
            $rules[] = match($language) {
                'en' => "If no company name is provided in context, write without mentioning a specific company name.",
                'de' => "Wenn kein Firmenname im Kontext angegeben ist, schreibe ohne spezifischen Firmennamen.",
                default => "Om inget företagsnamn finns i kontexten, skriv utan att nämna ett specifikt företagsnamn.",
            };
        }

        // KRITISK regel för bulk generation med variabler
        if (!empty($inputs['bulkVariables'])) {
            $varValues = [];
            foreach ($inputs['bulkVariables'] as $key => $value) {
                $varValues[] = "\"{$value}\"";
            }
            $valueList = implode(', ', $varValues);

            $rules[] = match($language) {
                'en' => "MANDATORY: The exact terms {$valueList} MUST each appear AT LEAST 3-5 times naturally distributed throughout the text. This is critical for local SEO and user requirements. Do NOT just mention once in intro.",
                'de' => "OBLIGATORISCH: Die exakten Begriffe {$valueList} MÜSSEN jeweils MINDESTENS 3-5 mal natürlich verteilt im Text vorkommen. Dies ist kritisch für lokales SEO und Benutzeranforderungen. NICHT nur einmal in Intro erwähnen.",
                default => "OBLIGATORISKT: De exakta termerna {$valueList} MÅSTE vardera förekomma MINST 3-5 gånger naturligt fördelade genom hela texten. Detta är kritiskt för lokal SEO och användarens krav. Nämn dem INTE bara en gång i introt.",
            };
        }

        return '- ' . implode("\n- ", $rules);
    }

    /**
     * Get length enforcement rule.
     */
    private function getLengthEnforcementRule(array $inputs): ?string
    {
        $lengthChars = $inputs['lengthChars'];
        $channel = $inputs['channel'];
        $blogWordLength = $inputs['blogWordLength'];

        if ($lengthChars !== '') {
            return "- Längdmål: {$lengthChars}. Justera texten så den landar där (±10%).";
        }

        if ($channel === self::CHANNEL_BLOG && isset(self::BLOG_CHAR_TARGET[$blogWordLength])) {
            $target = self::BLOG_CHAR_TARGET[$blogWordLength];
            return "- Längdmål: {$target}. Justera texten så den landar där (±10%).";
        }

        if ($channel === self::CHANNEL_BLOG) {
            $target = self::BLOG_CHAR_TARGET['default'];
            return "- Längdmål: {$target}. Justera texten så den landar där (±10%).";
        }

        return null;
    }

    /**
     * Get competitor protection rule.
     */
    private function getCompetitorProtectionRule(AiContent $content, ?string $linkUrl): string
    {
        if (!$content->site_id || !$content->site || !$content->site->domain) {
            return '';
        }

        $domain = parse_url($content->site->domain, PHP_URL_HOST) ?? $content->site->domain;
        $allowedDomains = [$domain];

        if ($linkUrl) {
            $linkHost = parse_url($linkUrl, PHP_URL_HOST);
            if ($linkHost && !in_array($linkHost, $allowedDomains, true)) {
                $allowedDomains[] = $linkHost;
            }
        }

        $domainList = implode(', ', $allowedDomains);
        return "- Om du hänvisar till webbsidor: använd ENDAST följande domäner: {$domainList}.\n- Inkludera ALDRIG andra företags domäner, webbsidor eller konkurrenters länkar.";
    }

    /**
     * Get blog structure note for blog channel.
     */
    private function getBlogStructureNote(array $inputs, string $language): string
    {
        if ($inputs['channel'] !== self::CHANNEL_BLOG) {
            return '';
        }

        return $language === 'en'
            ? "\nREMINDER: The title is handled separately. Start with intro paragraphs, then use H2/H3 subheadings."
            : "\nPÅMINNELSE: Titeln hanteras separat. Börja med introstycken, använd sedan H2/H3-underrubriker.";
    }

    /**
     * Get prompt labels based on language.
     */
    private function getPromptLabels(string $language): array
    {
        if ($language === 'en') {
            return [
                'role' => 'You are a professional content creator and copywriter with extensive experience in digital communication and marketing.',
                'context' => 'CONTEXT & BACKGROUND',
                'guidelines' => 'CUSTOM GUIDELINES',
                'channel' => 'CHANNEL-SPECIFIC RULES',
                'lengthTone' => 'LENGTH & TONE',
                'hard' => 'HARD RULES',
                'delivery' => 'DELIVERY FORMAT',
                'template' => 'CONTENT TEMPLATE (HINT – FOLLOW THE RULES ABOVE FIRST):',
                'deliveryBul1' => '- Write plain text (Markdown only in blog mode for headings/lists).',
                'deliveryBul2' => '- Exactly 1 version (no variant separator).',
                'bestPractice' => '- Use sound best practices for the channel',
            ];
        }

        return [
            'role' => 'Du är en professionell contentkraaatör och copywriter med stor erfarenhet av digital kommunikation och marknadsföring.',
            'context' => 'KONTEXT & BAKGRUND',
            'guidelines' => 'ANPASSADE RIKTLINJER',
            'channel' => 'KANALSPECIFIKA REGLER',
            'lengthTone' => 'LÄNGD OCH TON',
            'hard' => 'HÅRDA REGLER',
            'delivery' => 'LEVERANSFORMAT',
            'template' => 'INNEHÅLLSMALL (HINT – FÖLJ REGLERNA OVAN FÖRST):',
            'deliveryBul1' => '- Skriv ren text (Markdown endast i bloggläge för rubriker/listor).',
            'deliveryBul2' => '- Exakt 1 version (ingen variant-separator).',
            'bestPractice' => '- Använd sund bästa praxis för kanalen',
        ];
    }

    /**
     * Calculate cost based on tone.
     */
    private function calculateCost(string $tone): int
    {
        return $tone === 'short' ? self::COST_SHORT : self::COST_LONG;
    }

    /**
     * Calculate max tokens for generation.
     */
    private function calculateMaxTokens(AiContent $content, array $inputs): int
    {
        $baseMax = (int) ($content->template->max_tokens ?? self::TOKEN_BASE_MAX);
        $channel = $inputs['channel'];
        $tone = $inputs['tone'];

        // SEO channel: calculate based on original text length
        if ($channel === self::CHANNEL_SEO) {
            $origLen = mb_strlen((string) ($inputs['seoOptimize']['original_text'] ?? ''));
            if ($origLen > 0) {
                $calculated = max(200, (int) ceil(($origLen / 4) * 1.05));
                return min($baseMax, $calculated);
            }
        }

        // Blog channel: use presets
        if ($channel === self::CHANNEL_BLOG) {
            $blogLength = $inputs['blogWordLength'];
            $preset = self::TOKEN_BLOG[$blogLength] ?? self::TOKEN_BLOG['default'];
            return max($baseMax, $preset);
        }

        // Social channels
        if (in_array($channel, [self::CHANNEL_FACEBOOK, self::CHANNEL_INSTAGRAM, self::CHANNEL_LINKEDIN], true)) {
            $socialMax = $tone === 'short' ? self::TOKEN_SOCIAL_SHORT : self::TOKEN_SOCIAL_LONG;
            return max($baseMax, $socialMax);
        }

        return $baseMax;
    }

    /**
     * Get temperature setting for generation.
     */
    private function getTemperature(AiContent $content, string $channel): float
    {
        // Use lower temperature for SEO to maintain consistency
        if ($channel === self::CHANNEL_SEO) {
            return 0.2;
        }

        return (float) ($content->template->temperature ?? 0.7);
    }

    /**
     * Clean and normalize the AI output.
     */
    private function cleanOutput(string $output): string
    {
        $output = trim($output);

        // Remove markdown code blocks if present
        if (str_starts_with($output, '```')) {
            $output = preg_replace('/^```[a-zA-Z0-9_-]*\n?/', '', $output);
            $output = preg_replace("/\n?```$/", '', $output);
            $output = trim($output);
        }

        return $output;
    }

    /**
     * Ensure link is present in output if requested.
     */
    private function ensureLinkPresence(string $output, ?string $linkUrl, string $channel): string
    {
        if (!$linkUrl || $channel === self::CHANNEL_CAMPAIGN) {
            return $output;
        }

        if (stripos($output, $linkUrl) !== false) {
            return $output;
        }

        $suffix = $channel === self::CHANNEL_BLOG
            ? "\n\nLäs mer: {$linkUrl}"
            : "\n\n{$linkUrl}";

        return $output . $suffix;
    }

    /**
     * Dispatch image generation job if needed.
     */
    private function dispatchImageGenerationIfNeeded(AiContent $content, array $inputs): void
    {
        if (!$inputs['shouldGenImg'] || !$content->customer) {
            return;
        }

        $imagePrompt = $inputs['imagePrompt'] !== ''
            ? $inputs['imagePrompt']
            : ($content->title
                ? "Hero/illustration relaterad till: {$content->title}"
                : "Hero/illustration relaterad till innehållet");

        dispatch(new GenerateAiImageJob(
            (int) $content->customer->id,
            (int) $content->id,
            $imagePrompt,
            'blog',
            'photo'
        ))->onQueue('ai');
    }

    private function generateSeoTitle(AiContent $content, array $inputs, $provider, string $generatedBody): string
    {
        $language = $inputs['language'];
        $placeholders = $content->placeholders ?? [];

        // Create excerpt from generated body (first 300 chars)
        $excerpt = mb_substr(strip_tags($generatedBody), 0, 300);

        $titlePrompt = match($language) {
            'en' => "Create a short, catchy, SEO-optimized title (max 60 characters) for this content:\n\n{$excerpt}\n\nContext: " . json_encode($placeholders, JSON_UNESCAPED_SLASHES) . "\n\nProvide ONLY the title text, no quotes, no labels, just the title.",
            'de' => "Erstelle einen kurzen, eingängigen, SEO-optimierten Titel (max 60 Zeichen) für diesen Inhalt:\n\n{$excerpt}\n\nKontext: " . json_encode($placeholders, JSON_UNESCAPED_SLASHES) . "\n\nNur den Titeltext, keine Anführungszeichen, keine Labels.",
            default => "Skapa en kort, catchy, SEO-optimerad titel (max 60 tecken) för detta innehåll:\n\n{$excerpt}\n\nKontext: " . json_encode($placeholders, JSON_UNESCAPED_SLASHES) . "\n\nGe ENDAST titeltexten, inga citattecken, inga etiketter, bara titeln.",
        };

        try {
            $title = $provider->generate($titlePrompt, [
                'temperature' => 0.7,
                'max_tokens' => 60,
            ]);

            // Aggressive cleaning
            $title = trim($title);
            $title = preg_replace('/^["\'\[\](){}]+|["\'\[\](){}]+$/', '', $title); // Remove quotes and brackets
            $title = preg_replace('/^(Titel|Title|Rubrik|Headline):\s*/i', '', $title); // Remove labels
            $title = preg_replace('/^#+\s*/', '', $title); // Remove markdown headers

            // Limit length strictly
            if (mb_strlen($title) > 70) {
                // Try to cut at last space before 70 chars
                $title = mb_substr($title, 0, 70);
                $lastSpace = mb_strrpos($title, ' ');
                if ($lastSpace && $lastSpace > 50) {
                    $title = mb_substr($title, 0, $lastSpace);
                }
                $title .= '...';
            }

            Log::info("[BulkGen] Generated fallback title", [
                'content_id' => $content->id,
                'title' => $title,
            ]);

            return $title;
        } catch (\Throwable $e) {
            Log::error("[BulkGen] Failed to generate title", [
                'content_id' => $content->id,
                'error' => $e->getMessage(),
            ]);

            // Fallback: Use variable values
            $parts = array_slice(array_values($placeholders), 0, 2);
            $fallback = implode(' - ', array_filter($parts));
            return mb_substr($fallback ?: 'Genererad text', 0, 60);
        }
    }

    /**
     * Handle job failure.
     */
    private function handleFailure(AiContent $content, Throwable $e): void
    {
        $content->update([
            'status' => 'failed',
            'error' => $e->getMessage(),
        ]);
    }
}
