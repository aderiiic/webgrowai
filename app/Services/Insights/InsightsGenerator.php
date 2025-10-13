<?php

namespace App\Services\Insights;

use App\Models\Site;
use App\Services\AI\AnthropicProvider;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class InsightsGenerator
{
    public function __construct(
        private AnthropicProvider $claude // Kopplad via DI
    ) {}

    public function generateForWeek(Site $site, Carbon $weekStart): array
    {
        $context = $this->buildContext($site, $weekStart);

        try {
            $prompt = $this->buildPrompt($site, $context);
            $raw = $this->claude->generate($prompt, [
                'model'       => config('services.anthropic.model', 'claude-3-5-sonnet-latest'),
                'temperature' => 0.4,
                'max_tokens'  => 1200,
            ]);

            $parsed = $this->parseInsightsJson($raw);

            if ($this->isValid($parsed)) {
                $parsed['model'] = $this->claude->name();
                return $parsed;
            }
        } catch (\Throwable $e) {
            Log::error('Anthropic-fel vid insight-generering', [
                'site_id' => $site->id,
                'site_name' => $site->name,
                'error' => $e->getMessage(),
                'error_class' => get_class($e)
            ]);
        }

        return $this->fallbackHeuristics($context);
    }

    private function buildContext(Site $site, Carbon $weekStart): array
    {
        $homepage = [
            'title' => null,
            'meta'  => null,
            'text'  => null,
        ];

        try {
            if (!empty($site->url)) {
                $resp = Http::timeout(6)->get((string) $site->url);
                if ($resp->successful()) {
                    $html = (string) $resp->body();
                    if (preg_match('/<title[^>]*>(.*?)<\/title>/is', $html, $m)) {
                        $homepage['title'] = (string) Str::of($m[1])->stripTags()->squish();
                    }
                    if (preg_match('/<meta[^>]+name=["\']description["\'][^>]*content=["\']([^"\']+)["\']/i', $html, $m)) {
                        $homepage['meta'] = trim($m[1]);
                    } elseif (preg_match('/<meta[^>]+property=["\']og:description["\'][^>]*content=["\']([^"\']+)["\']/i', $html, $m)) {
                        $homepage['meta'] = trim($m[1]);
                    }
                    $clean = preg_replace('/<script[\s\S]*?<\/script>/i', ' ', $html);
                    $clean = preg_replace('/<style[\s\S]*?<\/style>/i', ' ', $clean);
                    $clean = preg_replace('/<(\/?h[1-3]|\/?p|br\s*\/?)>/i', "\n", $clean);
                    $clean = strip_tags($clean);
                    $clean = Str::of($clean)->replace(["\r"], '')->squish();
                    $homepage['text'] = Str::limit((string) $clean, 800, '...');
                }
            }
        } catch (\Throwable $e) {
            // tyst fail
        }

        return [
            'site' => [
                'id'          => $site->id,
                'name'        => $site->name,
                'url'         => $site->url,
                'industry'    => $site->industry,
                'description' => $site->business_description,
                'audience'    => $site->effectiveAudience(),
                'brand_voice' => $site->effectiveBrandVoice(),
                'goal'        => $site->effectiveGoal(),
                'keywords'    => $site->effectiveKeywords(), // per Site (ev. fallback från kund)
                'locale'      => $site->locale ?? 'sv_SE',
                'summary'     => $site->aiContextSummary(),
                'homepage'    => $homepage,
            ],
            'week' => [
                'start' => $weekStart->toDateString(),
                'end'   => (clone $weekStart)->addDays(6)->toDateString(),
                'label' => $weekStart->isoWeek().'/'. $weekStart->year,
            ],
        ];
    }

    private function buildPrompt(Site $site, array $ctx): string
    {
        $s = $ctx['site'];
        $w = $ctx['week'];

        $kwArr = (array)($s['keywords'] ?? []);
        $kwStr = !empty($kwArr) ? 'Nyckelord: '.implode(', ', array_slice($kwArr, 0, 12)) : 'Nyckelord: —';

        $hp = (array)($s['homepage'] ?? []);
        $hpBlock = [];
        if (!empty($hp['title'])) $hpBlock[] = "TITLE: {$hp['title']}";
        if (!empty($hp['meta']))  $hpBlock[] = "META: {$hp['meta']}";
        if (!empty($hp['text']))  $hpBlock[] = "TEXT: {$hp['text']}";
        $homepageSnippet = empty($hpBlock) ? '—' : implode("\n- ", $hpBlock);

        // Strikt JSON-krav + guardrails för att inte “läcka” mellan sajter
        return trim("
Du är en svensk senior content‑ och marknadsstrateg. Skapa KONKRETA och KORTFATTAT motiverade insikter för nedanstående sajt och vecka. Syftet är att guida vad man ska posta, när och varför.

KONTEKST (ENBART denna sajt)
- SITE_ID: {$s['id']}
- Site: {$s['name']} ({$s['url']})
- Vecka: {$w['start']} – {$w['end']} (v. {$w['label']})
- Sammanfattning: ".($s['summary'] ?: '—')."
- Bransch: ".($s['industry'] ?: '—')."
- Verksamhet: ".($s['description'] ?: '—')."
- Målgrupp: ".($s['audience'] ?: '—')."
- Ton: ".($s['brand_voice'] ?: 'neutral')."
- Veckans mål: ".($s['goal'] ?: '—')."
- {$kwStr}
- HOMEPAGE SNAPSHOT:
- {$homepageSnippet}

HÅRDA REGLER
- Anpassa uteslutande till sajten ovan. Blanda INTE in andra sajter från samma kund.
- Om verksamhetsbeskrivningen inte nämner fastigheter/fastighetsförvaltning: skriv inte om det.
- Prioritera det som explicit framgår i Verksamhet, Bransch och HOMEPAGE SNAPSHOT.
- Svara ENBART med strikt JSON enligt schema nedan (inga kodstaket, inga kommentarer).

JSON‑SCHEMA
{
  \"summary\": \"1–2 meningar som sätter fokus för veckan\",
  \"topics\": [{\"title\":\"\",\"why\":\"\"}, ... (3–6 st)],
  \"timeslots\": [{\"dow\":\"Måndag|Tisdag|Onsdag|Torsdag|Fredag|Lördag|Söndag\",\"time\":\"HH:MM\",\"why\":\"\"}, ... (2–5 st)],
  \"actions\": [{\"action\":\"\",\"why\":\"\"}, ... (2–5 st)],
  \"rationale\": \"1–2 meningar med övergripande motivering\"
}

REGLER FÖR INNEHÅLLET
- Svenska veckodagar och 24h‑format (t.ex. 09:00).
- Topics: praktiska idéer med kort \"why\", tydligt kopplat till denna sajt.
- Timeslots: specifika tider + kort \"why\".
- Actions: konkreta att‑göra med \"why\".
");
    }

    private function parseInsightsJson(string $raw): array
    {
        $t = trim($raw);

        // Ta bort ev. kodstaket om de smugit sig in
        if (str_starts_with($t, '```')) {
            $t = preg_replace('/^```[a-zA-Z0-9_-]*\n?/', '', $t);
            $t = preg_replace("/\n?```$/", '', $t);
            $t = trim((string)$t);
        }

        $data = json_decode($t, true);
        if (!is_array($data)) {
            // Försök extrahera första JSON-objekt
            if (preg_match('/\{.*\}/s', $t, $m)) {
                $data = json_decode($m[0], true);
            }
        }
        if (!is_array($data)) {
            throw new \RuntimeException('Kunde inte tolka JSON från Claude.');
        }

        // Normalisera
        $norm = [
            'summary'   => (string)($data['summary'] ?? ''),
            'topics'    => [],
            'timeslots' => [],
            'actions'   => [],
            'rationale' => (string)($data['rationale'] ?? ''),
        ];

        foreach ((array)($data['topics'] ?? []) as $t) {
            $norm['topics'][] = [
                'title' => (string)($t['title'] ?? ''),
                'why'   => (string)($t['why'] ?? ''),
            ];
        }

        foreach ((array)($data['timeslots'] ?? []) as $t) {
            $norm['timeslots'][] = [
                'dow'  => (string)($t['dow'] ?? ''),
                'time' => (string)($t['time'] ?? ''),
                'why'  => (string)($t['why'] ?? ''),
            ];
        }

        foreach ((array)($data['actions'] ?? []) as $t) {
            $norm['actions'][] = [
                'action' => (string)($t['action'] ?? ''),
                'why'    => (string)($t['why'] ?? ''),
            ];
        }

        return $norm;
    }

    private function isValid(array $d): bool
    {
        if ($d['summary'] === '' || $d['rationale'] === '') return false;
        if (empty($d['topics']) || empty($d['timeslots']) || empty($d['actions'])) return false;

        // Grundlig enkel validering av tider/dagar
        foreach ($d['timeslots'] as $s) {
            if (!preg_match('/^(Måndag|Tisdag|Onsdag|Torsdag|Fredag|Lördag|Söndag)$/u', $s['dow'] ?? '')) return false;
            if (!preg_match('/^\d{2}:\d{2}$/', $s['time'] ?? '')) return false;
        }
        return true;
    }

    /**
     * Heuristisk fallback om AI‑generering misslyckas.
     */
    private function fallbackHeuristics(array $ctx): array
    {
        $kw = array_values(array_filter(array_map('trim', (array)($ctx['site']['keywords'] ?? []))));
        $kw = array_slice($kw, 0, 6);

        $topics = [
            ['title' => 'Veckans trend i branschen', 'why' => 'Ökar relevans och trovärdighet mot målgruppen.'],
            ['title' => 'Kundcase eller micro‑case', 'why' => 'Bygger förtroende och social proof.'],
            ['title' => 'Praktiska tips (how‑to)', 'why' => 'Driver engagemang och sparningar.'],
            ['title' => 'Bakom kulisserna', 'why' => 'Mänskliggör varumärket och ökar relation.'],
        ];
        if (!empty($kw)) {
            $topics[] = ['title' => 'Keyword‑fokus: '.implode(', ', array_slice($kw, 0, 3)), 'why' => 'Stödjer synlighet för nyckelord.'];
        }

        $timeslots = [
            ['dow' => 'Tisdag',   'time' => '09:00', 'why' => 'Tidigt i arbetsdagen – hög öppnings-/scrollfrekvens.'],
            ['dow' => 'Torsdag',  'time' => '11:30', 'why' => 'Strax innan lunch – bra engagement‑fenster.'],
            ['dow' => 'Söndag',   'time' => '19:00', 'why' => 'Kvälls‑scroll inför veckan.'],
        ];

        $actions = [
            ['action' => 'Publicera 2–3 inlägg med tydlig CTA', 'why' => 'Tydlighet ökar klickfrekvensen.'],
            ['action' => 'A/B‑testa rubriker med 2 varianter',  'why' => 'Små rubrikskillnader kan ge stor effekt.'],
            ['action' => 'Återanvänd bästa inlägget i nyhetsbrev', 'why' => 'Maximerar räckvidd av bra innehåll.'],
        ];

        $summary = trim(implode(' ', array_filter([
            $ctx['site']['industry'] ? "Bransch: {$ctx['site']['industry']}." : null,
            $ctx['site']['audience'] ? "Målgrupp: {$ctx['site']['audience']}." : null,
            $ctx['site']['brand_voice'] ? "Ton: {$ctx['site']['brand_voice']}." : null,
            $ctx['site']['goal'] ? "Veckans mål: {$ctx['site']['goal']}." : null,
        ])));

        return [
            'summary'   => $summary ?: 'Översikt för aktuell vecka.',
            'topics'    => $topics,
            'timeslots' => $timeslots,
            'actions'   => $actions,
            'rationale' => 'Rekommendationerna balanserar räckvidd, engagemang och varumärkesbyggande.',
            'model'     => 'heuristics:v1',
        ];
    }
}
