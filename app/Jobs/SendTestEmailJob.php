<?php

namespace App\Jobs;

use App\Models\Customer;
use App\Models\AiContent;
use App\Models\NewsletterProduct;
use App\Services\AI\AiProviderManager;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class SendTestEmailJob implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public ?int $customerId,
        public int $siteId,
        public string $testEmail,
        public string $subject,
        public ?string $htmlContent = null,
        public int $numItems = 3,
        public bool $includeWebsiteContent = false,
        public bool $includeProducts = false,
        public array $selectedProducts = [],
        public array $customImages = [],
        public bool $isCustom = false
    ) {
        $this->onQueue('mail');
    }

    private function sanitize(string $text): string
    {
        // Ta bort AI-fraseer och markdown-kod
        $text = preg_replace('/^(Självklart|Här är|Absolut|Givetvis)[\p{L}\p{M}\p{Zs}\p{P}]*?:\s*/ui', '', trim($text)) ?? $text;
        $text = preg_replace('/\b(som AI|jag kan|jag har|i denna text)\b/iu', '', $text) ?? $text;

        // Ta bort markdown kodblock-markeringar
        $text = preg_replace('/^```[\w]*\s*/m', '', $text);
        $text = preg_replace('/\s*```$/m', '', $text);

        return trim($text);
    }

    public function handle(AiProviderManager $manager): void
    {
        try {
            if ($this->isCustom) {
                $this->sendEmail($this->subject, $this->htmlContent);
                return;
            }

            if (!$this->customerId) {
                throw new \RuntimeException('Customer ID krävs för AI-genererat testmejl.');
            }

            $customer = Customer::findOrFail($this->customerId);
            $site = $customer->sites()->whereKey($this->siteId)->first();

            // Hämta AI-innehåll
            $posts = AiContent::where('customer_id', $customer->id)
                ->whereNotNull('body_md')
                ->latest()
                ->take($this->numItems)
                ->get(['title', 'body_md', 'created_at']);

            // Generera intro
            $prov = $manager->choose(null, 'short');
            $guidelines = view('prompts.newsletter_guidelines')->render();
            $introPrompt = $guidelines . "\n\nSkriv en kort inledning (2–3 meningar) för ett nyhetsbrev till målgruppen. Ton: professionell, saklig, inbjudande. Returnera endast ren text utan meta-fraser eller HTML.";
            $intro = $this->sanitize($prov->generate($introPrompt, ['max_tokens' => 120, 'temperature' => 0.4])) ?: "Detta är en förhandsvisning av ditt kommande nyhetsbrev.";

            // Bearbeta artiklar med bättre HTML-generering
            $items = [];
            foreach ($posts as $p) {
                $prompt = "Skriv en kort, engagerande sammanfattning (3-4 meningar) av följande artikel: \"{$p->title}\"

                Baserad på: " . Str::limit($p->body_md, 1500) . "

                Skriv endast ren text, ingen HTML eller markdown. Gör det säljande och inspirerande.";

                $summary = $this->sanitize($prov->generate($prompt, ['max_tokens' => 180, 'temperature' => 0.6]));

                // Konvertera till ren HTML
                $html = "<p>" . nl2br(e($summary)) . "</p>";

                $items[] = [
                    'title' => $p->title ?? 'Avsnitt',
                    'html' => $html,
                ];
            }

            // Lägg till användarens egna produkter/tjänster om valda
            if ($this->includeProducts && !empty($this->selectedProducts)) {
                $productItems = $this->generateSelectedProductItems($customer, $site, $manager);
                $items = array_merge($items, $productItems);
            }

            // Använd riktig webbadress
            $websiteUrl = $customer->website ?? $site?->url ?? 'https://exempel.se';
            if ($websiteUrl === 'https://exempel.se') {
                $websiteUrl = 'mailto:' . ($customer->contact_email ?? 'hej@exempel.se');
            }

            // Bygg HTML med avprenumerationslänk
            $html = view('marketing.newsletter-html', [
                'title' => $this->subject,
                'intro' => $intro . "\n\n<p><strong>🧪 Detta är ett testmejl</strong> - så kommer ditt nyhetsbrev att se ut.</p>",
                'items' => $items,
                'customImages' => $this->customImages,
                'cta_label' => 'Kontakta oss',
                'cta_url' => $websiteUrl,
                'company_name' => $customer->name ?? $customer->company_name ?? 'Vårt företag',
                'unsubscribe_text' => 'Du kan avregistrera dig från vårt nyhetsbrev när som helst via Mailchimp.',
                'unsubscribe_url' => '*|UNSUB|*', // Mailchimp merge tag
                'isTest' => true,
            ])->render();

            $this->sendEmail("[TEST] " . $this->subject, $html);

        } catch (\Exception $e) {
            \Log::error('SendTestEmailJob failed', [
                'error' => $e->getMessage(),
                'customer_id' => $this->customerId,
                'test_email' => $this->testEmail,
            ]);
            throw $e;
        }
    }

    private function generateSelectedProductItems(Customer $customer, $site, AiProviderManager $manager): array
    {
        $items = [];

        // Hämta valda produkter från databasen
        $products = NewsletterProduct::whereIn('id', $this->selectedProducts)
            ->where('is_active', true)
            ->get();

        foreach ($products as $product) {
            // Generera AI-text för produkten baserat på titel och beskrivning
            $prompt = "Skriv en kort, säljande beskrivning (2-3 meningar) för följande produkt/tjänst:

            Titel: {$product->title}
            Beskrivning: {$product->description}

            Gör texten engagerande och professionell. Fokusera på värdet för kunden. Skriv endast ren text utan HTML.";

            $aiDescription = $this->sanitize($manager->choose(null, 'short')->generate($prompt, [
                'max_tokens' => 150,
                'temperature' => 0.6
            ]));

            // Fallback till original beskrivning om AI misslyckas
            $description = $aiDescription ?: $product->description;

            // Bygg HTML för produkten
            $html = "<p>" . nl2br(e($description)) . "</p>";

            // Lägg till pris om det finns
            if ($product->price) {
                $html .= "<p><strong>Pris: {$product->price}</strong></p>";
            }

            // Lägg till bild om den finns
            if ($product->image_url) {
                $html = "<div style='margin-bottom: 16px;'><img src='{$product->image_url}' alt='" . e($product->title) . "' style='max-width: 100%; height: auto; border-radius: 8px;'></div>" . $html;
            }

            // Lägg till länk
            $html .= "<p><a href='{$product->url}' style='color: #ea580c; text-decoration: none; font-weight: 600;'>Läs mer och beställ →</a></p>";

            $items[] = [
                'title' => $product->title,
                'html' => $html,
                'type' => 'product'
            ];
        }

        return $items;
    }

    private function sendEmail(string $subject, string $html): void
    {
        Mail::html($html, function ($message) use ($subject) {
            $message->to($this->testEmail)
                ->subject($subject)
                ->from(config('mail.from.address'), config('mail.from.name'));
        });
    }
}
