<?php

namespace App\Livewire\Marketing;

use App\Support\CurrentCustomer;
use GuzzleHttp\Client;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class MailchimpHistory extends Component
{
    public array $campaigns = [];
    public string $statusFilter = ''; // 'sent'|'scheduled'|'save'|''

    public function mount(CurrentCustomer $current): void
    {
        $this->loadCampaigns($current);
    }

    public function updatedStatusFilter(CurrentCustomer $current): void
    {
        $this->loadCampaigns($current);
    }

    private function loadCampaigns(CurrentCustomer $current): void
    {
        $this->campaigns = [];
        $c = $current->get();
        if (!$c || !$c->mailchimp_api_key) return;

        $key = decrypt($c->mailchimp_api_key);
        $dc  = explode('-', $key)[1] ?? config('services.mailchimp.default_dc');
        if (!$dc) return;

        $http = new Client([
            'base_uri' => "https://{$dc}.api.mailchimp.com/3.0/",
            'timeout'  => 20,
            'auth'     => ['anystring', $key],
        ]);

        $query = ['count' => 20, 'sort_field' => 'create_time', 'sort_dir' => 'DESC'];
        if ($this->statusFilter !== '') $query['status'] = $this->statusFilter;

        $res = $http->get('campaigns', ['query' => $query]);
        $data = json_decode((string) $res->getBody(), true);
        $this->campaigns = $data['campaigns'] ?? [];
    }

    public function render()
    {
        return view('livewire.marketing.mailchimp-history');
    }
}
