<?php

namespace App\Livewire\Marketing;

use App\Jobs\GenerateNewsletterFromAIJob;
use App\Support\CurrentCustomer;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class NewsletterCompose extends Component
{
    public string $subject = '';
    public ?string $sendAt = null; // datetime-local
    public int $numItems = 3;

    public function submit(CurrentCustomer $current): void
    {
        $this->validate([
            'subject' => 'required|string|min:3|max:140',
            'sendAt'  => 'nullable|date',
            'numItems'=> 'required|integer|min:1|max:10',
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
            session()->flash('error', 'Du måste ansluta ett konto.');
            return;
        }

        dispatch(new GenerateNewsletterFromAIJob(
            customerId: $c->id,
            subject: $this->subject,
            sendAtIso: $this->sendAt ? \Illuminate\Support\Carbon::parse($this->sendAt)->toIso8601String() : null,
            numItems: $this->numItems
        ))->onQueue('ai');

        session()->flash('success', 'Nyhetsbrevet köades. Kontrollera Mailchimp för leverans/schemaläggning.');
    }

    public function render()
    {
        return view('livewire.marketing.newsletter-compose');
    }
}
