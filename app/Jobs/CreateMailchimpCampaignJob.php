<?php

namespace App\Jobs;

use App\Models\Site;
use App\Services\Mailchimp\MailchimpClient;
use App\Support\Usage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class CreateMailchimpCampaignJob implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public int $siteId,
        public string $subject,
        public string $htmlContent,
        public ?string $sendAtIso = null
    ) {
        $this->onQueue('mail');
    }

    public function handle(Usage $usage): void
    {
        $site = Site::with('customer')->findOrFail($this->siteId);

        $key = $site->mailchimp_api_key ? decrypt($site->mailchimp_api_key) : null;
        $list = $site->mailchimp_audience_id;
        $from = $site->mailchimp_from_name;
        $reply = $site->mailchimp_reply_to;

        if (!$key || !$list || !$from || !$reply) {
            throw new \RuntimeException('Sajtens Mailchimp-inställningar är ofullständiga.');
        }

        try {
            $mc = new MailchimpClient($key);

            $campaign = $mc->createCampaign($list, $from, $reply, $this->subject);
            $campaignId = $campaign['id'] ?? null;

            if (!$campaignId) {
                throw new \RuntimeException('Kunde inte skapa kampanj i Mailchimp.');
            }

            $mc->setContent($campaignId, $this->htmlContent);

            if ($this->sendAtIso) {
                $mc->schedule($campaignId, new \DateTimeImmutable($this->sendAtIso));
                \Log::info("Mailchimp campaign scheduled", [
                    'campaign_id' => $campaignId,
                    'subject' => $this->subject,
                    'send_at' => $this->sendAtIso,
                    'site_id' => $this->siteId,
                ]);
            } else {
                $mc->sendNow($campaignId);
                \Log::info("Mailchimp campaign sent", [
                    'campaign_id' => $campaignId,
                    'subject' => $this->subject,
                    'site_id' => $this->siteId,
                ]);
            }

            $usage->increment($site->customer_id, 'mailchimp.campaign');

        } catch (\Exception $e) {
            \Log::error('CreateMailchimpCampaignJob failed', [
                'error' => $e->getMessage(),
                'site_id' => $this->siteId,
                'subject' => $this->subject,
            ]);
            throw $e;
        }
    }
}
