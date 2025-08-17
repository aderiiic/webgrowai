<?php

namespace App\Jobs;

use App\Models\PostSuggestion;
use App\Support\Usage;
use GuzzleHttp\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;

class GenerateLinkedInSuggestionsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public int $customerId,
        public string $topic,
        public ?string $tone = null,
        public int $count = 1
    ) {
        $this->onQueue('ai');
    }

    public function handle(Usage $usage): void
    {
        $texts = $this->generateViralPosts($this->topic, $this->tone, 1);

        $recommendedTimes = $this->recommendedTimes();

        foreach ($texts as $t) {
            PostSuggestion::create([
                'customer_id'        => $this->customerId,
                'provider'           => 'linkedin',
                'topic'              => $this->topic,
                'content'            => $t,
                'recommended_times'  => $recommendedTimes,
                'expires_at'         => Carbon::now()->addDays(5),
            ]);

            // Räkna AI-generering – en per förslag (samma mönster som GenerateContentJob)
            $usage->increment($this->customerId, 'ai.generate');
        }
    }

    private function recommendedTimes(): array
    {
        $tz = config('app.timezone', 'Europe/Stockholm');
        $now = Carbon::now($tz);
        $slots = ['08:30', '11:45', '16:30'];
        $days = [2, 3, 4]; // Tis–Tors
        $out = [];
        foreach ($days as $d) {
            $date = $now->copy()->next($d);
            foreach ($slots as $time) {
                [$h,$m] = explode(':', $time);
                $out[] = $date->copy()->setTime((int)$h,(int)$m)->toIso8601String();
            }
        }
        return $out;
    }

    /**
     * Generera kompletta LinkedIn-inlägg i “viral”-stil.
     */
    private function generateViralPosts(string $topic, ?string $tone, int $count): array
    {
        $apiKey = config('services.openai.key');
        if (!$apiKey) {
            // Fallback (om API-nyckel saknas): returnera något vettigt men komplett
            return $this->fallbackPosts($topic, $tone, $count);
        }

        $client = new Client(['base_uri' => 'https://api.openai.com/v1/', 'timeout' => 60]);

        $system = "Du skriver svenska LinkedIn-inlägg i viral stil.
- Hook i de första 1–2 raderna.
- 3–7 korta stycken (1–3 meningar vardera), lätta att skumma.
- 0–2 relevanta emojis max.
- Avsluta med en CTA-fråga.
- Lägg 5-6 relevanta hashtags längst ner.
- Inga rubriker med # i texten, inga mentions, inga länkar.
- 120–220 ord totalt.";
        $user = "Ämne: {$topic}\nTon: ".($tone ?: 'professionell, hjälpsam och konkret')."\nMål: Maximera engagemang (likes, kommentarer, sparningar). Skriv ett helt inlägg enligt riktlinjerna.";

        // Vi ber modellen generera flera inlägg i ett svar (num_responses via loop för robusthet)
        $results = [];
        for ($i = 0; $i < $count; $i++) {
            $res = $client->post('chat/completions', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $apiKey,
                    'Content-Type'  => 'application/json',
                ],
                'json' => [
                    'model' => 'gpt-4o-mini',
                    'temperature' => 0.8,
                    'messages' => [
                        ['role' => 'system', 'content' => $system],
                        ['role' => 'user',   'content' => $user],
                    ],
                ],
            ]);

            $data = json_decode((string)$res->getBody(), true);
            $text = trim($data['choices'][0]['message']['content'] ?? '');
            if (!$text) {
                $text = $this->fallbackPosts($topic, $tone, 1)[0];
            }
            $results[] = $text;
        }

        return $results;
    }

    private function fallbackPosts(string $topic, ?string $tone, int $count): array
    {
        $posts = [];
        for ($i = 1; $i <= $count; $i++) {
            $posts[] = "Det här är varför {$topic} avgör vem som vinner – inte bara vem som jobbar hårdast.

• Insikt #$i: Konkret tips om {$topic} som andra ofta missar.
• Så gör du: 1) Kort steg 2) Kort steg 3) Kort steg
• Undvik: Vanlig fälla och hur du ser den i tid.

När vi testade detta ökade vi engagemanget med 23% på 14 dagar – utan större budget. Små, konsekventa förbättringar slår sporadiska kampanjer.

Vad är din största utmaning med {$topic} just nu?

#{$this->hashtagFrom($topic)}";
        }
        return $posts;
    }

    private function hashtagFrom(string $topic): string
    {
        $slug = strtolower(preg_replace('/[^a-z0-9]+/i', '', $topic));
        return $slug ?: 'marketing';
    }
}
