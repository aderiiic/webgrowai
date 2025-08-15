<?php

namespace App\Jobs;

use App\Models\AiContent;
use App\Models\Customer;
use App\Services\AI\AiProviderManager;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Str;

class GenerateNewsletterFromAIJob implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public int $customerId,
        public string $subject,
        public ?string $sendAtIso = null,
        public int $numItems = 3
    ) {
        $this->onQueue('ai');
    }

    private function sanitize(string $text): string
    {
        // Ta bort vanliga AI-fraseer i början
        $text = preg_replace('/^(Självklart|Här är|Absolut|Givetvis)[\p{L}\p{M}\p{Zs}\p{P}]*?:\s*/ui', '', trim($text)) ?? $text;
        // Minimera garderingar
        $text = preg_replace('/\b(som AI|jag kan|jag har|i denna text)\b/iu', '', $text) ?? $text;
        return trim($text);
    }

    public function handle(AiProviderManager $manager): void
    {
        $customer = Customer::findOrFail($this->customerId);

        // Hämta de senaste AI-innehållen (bloggar/artiklar)
        $posts = AiContent::where('customer_id', $customer->id)
            ->whereNotNull('body_md')
            ->latest()
            ->take($this->numItems)
            ->get(['title','body_md','created_at']);

        // Kort introt ext – AI kan förbättra men utan meta-ton
        $prov = $manager->choose(null, 'short');
        $guidelines = view('prompts.newsletter_guidelines')->render();
        $introPrompt = $guidelines."\n\nSkriv en kort inledning (2–3 meningar) för ett nyhetsbrev till målgruppen. Ton: professionell, saklig, inbjudande. Använd inte meta-fraser.";
        $intro = $this->sanitize($prov->generate($introPrompt, ['max_tokens' => 120, 'temperature' => 0.4])) ?: "Nedan hittar du aktuella lästips och nyheter från oss.";

        // För varje post – extrahera en kort HTML-sammanfattning (utan AI-fraser)
        $items = [];
        foreach ($posts as $p) {
            $prompt = $guidelines.
                "\n\nSkriv en kort sammanfattning (3–5 meningar) i HTML för rubriken: \"{$p->title}\".\n".
                "Utgå från texten nedan (markdown). Undvik meta-fraser, inga emojis.\n\n".
                Str::limit($p->body_md, 2000);

            $html = $prov->generate($prompt, ['max_tokens' => 220, 'temperature' => 0.5]);
            $html = $this->sanitize($html);

            // Om AI skulle råka returnera Markdown – gör en enkel konvertering
            if (strip_tags($html) === $html) {
                $html = Str::of($html)->markdown()->toString();
            }

            $items[] = [
                'title' => $p->title ?? 'Avsnitt',
                'html'  => $html,
            ];
        }

        // Bygg HTML via Blade-mall
        $html = view('marketing.newsletter-html', [
            'title'     => $this->subject,
            'intro'     => $intro,
            'items'     => $items,
            'cta_label' => 'Boka demo',
            'cta_url'   => $customer->website ?? null,
        ])->render();

        // Skapa/schemalägg kampanj
        dispatch(new CreateMailchimpCampaignJob(
            customerId: $customer->id,
            subject: $this->subject,
            htmlContent: $html,
            sendAtIso: $this->sendAtIso
        ))->onQueue('mail');
    }
}
