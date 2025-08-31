<?php

namespace App\Livewire\Marketing;

use App\Jobs\GenerateNewsletterFromAIJob;
use App\Jobs\CreateMailchimpCampaignJob;
use App\Jobs\SendTestEmailJob;
use App\Models\NewsletterProduct;
use App\Support\CurrentCustomer;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;

#[Layout('layouts.app')]
class NewsletterCompose extends Component
{
    use WithFileUploads;

    // Creation mode
    public string $creationMode = 'ai';

    // AI Mode properties
    public string $subject = '';
    public ?string $sendAt = null;
    public int $numItems = 3;
    public bool $includeWebsiteContent = false;
    public bool $includeProducts = false;
    public bool $includeCustomImages = false;
    public $images = [];
    public string $testEmail = '';

    // Product management
    public array $selectedProducts = [];
    public bool $showProductManager = false;
    public string $newProductTitle = '';
    public string $newProductDescription = '';
    public string $newProductUrl = '';
    public string $newProductPrice = '';
    public string $newProductImage = '';

    // Custom HTML Mode properties
    public string $customSubject = '';
    public string $customHtml = '';
    public ?string $customSendAt = null;

    public function mount(): void
    {
        $this->testEmail = auth()->user()?->email ?? '';
    }

    public function updatedIncludeProducts(): void
    {
        if ($this->includeProducts) {
            $this->loadSelectedProducts();
        }
    }

    public function toggleProductManager(): void
    {
        $this->showProductManager = !$this->showProductManager;
        if ($this->showProductManager) {
            $this->loadSelectedProducts();
        }
    }

    public function removeProduct(int $productId, CurrentCustomer $current): void
    {
        $customer = $current->get();
        $siteId = $current->getSiteId();
        abort_unless($customer && $siteId, 403);

        $site = $customer->sites()->whereKey($siteId)->first();
        if ($site) {
            $site->newsletterProducts()->where('id', $productId)->delete();
            $this->selectedProducts = array_filter($this->selectedProducts, fn($id) => $id !== $productId);

            session()->flash('success', 'Produkt borttagen!');
        }
    }

    public function submit(CurrentCustomer $current): void
    {
        $this->validate([
            'subject' => 'required|string|min:3|max:140',
            'sendAt'  => 'nullable|date',
            'numItems'=> 'required|integer|min:1|max:10',
            'testEmail' => 'nullable|email',
            'selectedProducts' => 'nullable|array',
            'selectedProducts.*' => 'integer|exists:newsletter_products,id',
        ]);

        $c = $current->get();
        abort_unless($c, 403);

        $site = $c->sites()->whereKey($current->getSiteId())->first();
        if (!$site) {
            session()->flash('error', 'Ingen sajt vald.');
            return;
        }

        // Kräv kopplat Mailchimp-konto på SAJTEN
        if (!$site->mailchimp_api_key || !$site->mailchimp_audience_id || !$site->mailchimp_from_name || !$site->mailchimp_reply_to) {
            session()->flash('error', 'Du måste ansluta ett Mailchimp-konto först. Gå till Inställningar → Mailchimp.');
            return;
        }

        dispatch(new GenerateNewsletterFromAIJob(
            customerId: $c->id,
            siteId: $current->getSiteId(),
            subject: $this->subject,
            sendAtIso: $this->sendAt ? \Illuminate\Support\Carbon::parse($this->sendAt)->toIso8601String() : null,
            numItems: $this->numItems,
            includeWebsiteContent: $this->includeWebsiteContent,
            includeProducts: $this->includeProducts,
            selectedProducts: $this->selectedProducts,
            customImages: $this->processUploadedImages()
        ))->onQueue('ai');

        session()->flash('success', 'Nyhetsbrevet köades för generering. Kontrollera Mailchimp om en stund.');

        // Reset form
        $this->reset(['subject', 'sendAt', 'numItems', 'includeWebsiteContent', 'includeProducts', 'includeCustomImages', 'images', 'selectedProducts']);
    }

    public function sendTestEmail(CurrentCustomer $current): void
    {
        $this->validate([
            'subject' => 'required|string|min:3|max:140',
            'testEmail' => 'required|email',
            'selectedProducts' => 'nullable|array',
            'selectedProducts.*' => 'integer|exists:newsletter_products,id',
        ]);

        if (empty($this->testEmail)) {
            session()->flash('error', 'Ange en e-postadress för testet.');
            return;
        }

        $c = $current->get();
        abort_unless($c, 403);

        dispatch(new SendTestEmailJob(
            customerId: $c->id,
            siteId: $current->getSiteId(),
            testEmail: $this->testEmail,
            subject: $this->subject,
            numItems: $this->numItems,
            includeWebsiteContent: $this->includeWebsiteContent,
            includeProducts: $this->includeProducts,
            selectedProducts: $this->selectedProducts,
            customImages: $this->processUploadedImages()
        ))->onQueue('mail');

        session()->flash('success', "Testmejl skickat till {$this->testEmail}");
    }

    // ... resten av metoderna är samma som tidigare ...

    private function processUploadedImages(): array
    {
        $processedImages = [];

        if (!empty($this->images)) {
            foreach ($this->images as $image) {
                if ($image->isValid()) {
                    $path = $image->store('newsletter-images', 'public');
                    $processedImages[] = [
                        'path' => $path,
                        'url' => asset('storage/' . $path),
                        'name' => $image->getClientOriginalName(),
                        'size' => $image->getSize(),
                    ];
                }
            }
        }

        return $processedImages;
    }


    private function loadSelectedProducts(): void
    {
        $current = app(CurrentCustomer::class);
        $customer = $current->get();
        $siteId = $current->getSiteId();

        if ($customer && $siteId) {
            $site = $customer->sites()->whereKey($siteId)->first();
            if ($site) {
                $products = $site->newsletterProducts()->where('is_active', true)->get();
                $this->selectedProducts = $products->pluck('id')->toArray();
            }
        }
    }

    public function addProduct(CurrentCustomer $current): void
    {
        $this->validate([
            'newProductTitle' => 'required|string|max:255',
            'newProductDescription' => 'nullable|string|max:1000',
            'newProductUrl' => 'required|url|max:500',
            'newProductPrice' => 'nullable|string|max:100',
            'newProductImage' => 'nullable|url|max:500',
        ]);

        $customer = $current->get();
        $siteId = $current->getSiteId();
        abort_unless($customer && $siteId, 403);

        $site = $customer->sites()->whereKey($siteId)->first();
        if (!$site) {
            session()->flash('error', 'Ingen sajt vald.');
            return;
        }

        $product = $site->newsletterProducts()->create([
            'title' => $this->newProductTitle,
            'description' => $this->newProductDescription,
            'url' => $this->newProductUrl,
            'price' => $this->newProductPrice,
            'image_url' => $this->newProductImage,
            'sort_order' => $site->newsletterProducts()->max('sort_order') + 1,
        ]);

        $this->selectedProducts[] = $product->id;

        // Reset form
        $this->reset(['newProductTitle', 'newProductDescription', 'newProductUrl', 'newProductPrice', 'newProductImage']);

        session()->flash('success', 'Produkt tillagd!');
    }

    public function render(CurrentCustomer $current)
    {
        $customer = $current->get();
        $siteId = $current->getSiteId();

        $availableProducts = collect();
        if ($customer && $siteId) {
            $site = $customer->sites()->whereKey($siteId)->first();
            if ($site) {
                $availableProducts = $site->newsletterProducts()->where('is_active', true)->get();
            }
        }

        return view('livewire.marketing.newsletter-compose', [
            'availableProducts' => $availableProducts,
        ]);
    }

    public function updatedIncludeCustomImages(): void
    {
        // Inget behöver göras här, bilduppladdningen visas automatiskt
        // men metoden behövs för att uppdateringen ska fungera smidigt
    }
}
