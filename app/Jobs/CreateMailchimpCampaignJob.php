<?php

namespace App\Jobs;

use App\Models\Customer;
use App\Services\Mailchimp\MailchimpClient;
use App\Support\Usage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class CreateMailchimpCampaignJob implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public int $customerId,
        public string $subject,
        public string $htmlContent,
        public ?string $sendAtIso = null // null = skicka nu
    ) {
        $this->onQueue('mail');
    }

    public function handle(Usage $usage): void
    {
        $c = Customer::findOrFail($this->customerId);

        $key = $c->mailchimp_api_key ? decrypt($c->mailchimp_api_key) : null;
        $listId = $c->mailchimp_audience_id;
        $from = $c->mailchimp_from_name;
        $reply = $c->mailchimp_reply_to;

        if (!$key || !$listId || !$from || !$reply) {
            throw new \RuntimeException('Kundens Mailchimp-inställningar är ofullständiga.');
        }

        $mc = new MailchimpClient($key);

        // 1) Skapa kampanj
        $campaign = $mc->createCampaign($listId, $from, $reply, $this->subject);
        $campaignId = $campaign['id'] ?? null;
        if (!$campaignId) {
            throw new \RuntimeException('Kunde inte skapa kampanj.');
        }

        // 2) Sätt innehåll
        $mc->setContent($campaignId, $this->htmlContent);

        // 3) Skicka eller schemalägg
        if ($this->sendAtIso) {
            $mc->schedule($campaignId, new \DateTimeImmutable($this->sendAtIso));
        } else {
            $mc->sendNow($campaignId);
        }

        // Usage
        $usage->increment($c->id, 'mailchimp.campaign');
    }
}
