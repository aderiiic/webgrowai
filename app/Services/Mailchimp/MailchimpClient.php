<?php

namespace App\Services\Mailchimp;

use GuzzleHttp\Client;

class MailchimpClient
{
    private Client $http;

    public function __construct(private string $apiKey)
    {
        $dc = explode('-', $apiKey)[1] ?? config('services.mailchimp.default_dc');
        if (!$dc) {
            throw new \InvalidArgumentException('Kunde inte avgöra Mailchimp datacenter (DC).');
        }

        $this->http = new Client([
            'base_uri' => "https://{$dc}.api.mailchimp.com/3.0/",
            'timeout' => 30,
            'auth' => ['anystring', $this->apiKey], // Basic auth: anystring:APIKEY
        ]);
    }

    public function createCampaign(string $listId, string $fromName, string $replyTo, string $subject): array
    {
        $res = $this->http->post('campaigns', [
            'json' => [
                'type' => 'regular',
                'recipients' => ['list_id' => $listId],
                'settings' => [
                    'subject_line' => $subject,
                    'from_name'    => $fromName,
                    'reply_to'     => $replyTo,
                ],
            ],
        ]);

        return json_decode((string) $res->getBody(), true);
    }

    public function setContent(string $campaignId, string $html): void
    {
        $this->http->put("campaigns/{$campaignId}/content", [
            'json' => [
                'html' => $html,
            ],
        ]);
    }

    public function schedule(string $campaignId, \DateTimeInterface $sendAt): void
    {
        $this->http->post("campaigns/{$campaignId}/actions/schedule", [
            'json' => [
                'schedule_time' => $sendAt->format('c'),
            ],
        ]);
    }

    public function sendNow(string $campaignId): void
    {
        $this->http->post("campaigns/{$campaignId}/actions/send", ['json' => (object)[]]);
    }
}
