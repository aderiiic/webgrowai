<?php

namespace App\Livewire\Settings;

use App\Support\CurrentCustomer;
use GuzzleHttp\Client;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class MailchimpSettings extends Component
{
    public string $api_key = '';
    public string $audience_id = '';
    public string $from_name = '';
    public string $reply_to = '';
    public ?string $status = null;
    public ?string $message = null;

    public function mount(CurrentCustomer $current): void
    {
        $c = $current->get();
        abort_unless($c, 403);

        // Visa inte befintlig api_key i klartext
        $this->api_key = (string) ($c->mailchimp_api_key ? '' : '');
        $this->audience_id = (string) ($c->mailchimp_audience_id ?? '');
        $this->from_name   = (string) ($c->mailchimp_from_name ?? '');
        $this->reply_to    = (string) ($c->mailchimp_reply_to ?? '');
    }

    public function save(CurrentCustomer $current): void
    {
        $c = $current->get();
        abort_unless($c, 403);

        $this->validate([
            'api_key'     => 'nullable|string|max:255',
            'audience_id' => 'nullable|string|max:120',
            'from_name'   => 'nullable|string|max:120',
            'reply_to'    => 'nullable|email',
        ]);

        // ForceFill förbi mass assignment (och undvik att skriva över api_key om tom)
        $payload = [
            'mailchimp_audience_id' => $this->audience_id ?: null,
            'mailchimp_from_name'   => $this->from_name ?: null,
            'mailchimp_reply_to'    => $this->reply_to ?: null,
        ];
        if ($this->api_key !== '') {
            $payload['mailchimp_api_key'] = encrypt($this->api_key);
        }

        $c->forceFill($payload)->save();

        // Återställ API-fältet (visa aldrig hemligheter)
        $this->api_key = '';
        session()->flash('success', 'Mailchimp-inställningarna sparade.');
    }

    public function test(CurrentCustomer $current): void
    {
        $this->message = null;
        $c = $current->get();
        abort_unless($c, 403);

        $key = $this->api_key !== ''
            ? $this->api_key
            : ($c->mailchimp_api_key ? decrypt($c->mailchimp_api_key) : '');

        if (!$key) {
            $this->status = 'error';
            $this->message = 'API-nyckel saknas.';
            return;
        }

        $dc = explode('-', $key)[1] ?? config('services.mailchimp.default_dc');
        if (!$dc) {
            $this->status = 'error';
            $this->message = 'Kunde inte avgöra datacenter (DC).';
            return;
        }

        try {
            $http = new Client([
                'base_uri' => "https://{$dc}.api.mailchimp.com/3.0/",
                'timeout'  => 20,
                'auth'     => ['anystring', $key],
            ]);
            $res = $http->get('lists', ['query' => ['count' => 1]]);
            $data = json_decode((string) $res->getBody(), true);
            $total = $data['total_items'] ?? 0;

            $this->status = 'active';
            $this->message = "OK: API fungerar. Tillgängliga listor: {$total}.";
        } catch (\Throwable $e) {
            $this->status = 'error';
            $this->message = 'Fel: '.$e->getMessage();
        }
    }

    public function render()
    {
        return view('livewire.settings.mailchimp-settings');
    }
}
