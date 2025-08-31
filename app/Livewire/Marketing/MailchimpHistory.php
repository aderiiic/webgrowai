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
    public string $statusFilter = '';
    public string $searchTerm = '';
    public ?string $dateFrom = null;
    public ?string $dateTo = null;
    public int $offset = 0;
    public int $limit = 20;

    public function mount(CurrentCustomer $current): void
    {
        $this->loadCampaigns($current);
    }

    public function updatedStatusFilter(CurrentCustomer $current): void
    {
        $this->offset = 0;
        $this->loadCampaigns($current);
    }

    public function updatedSearchTerm(CurrentCustomer $current): void
    {
        $this->offset = 0;
        $this->loadCampaigns($current);
    }

    public function updatedDateFrom(CurrentCustomer $current): void
    {
        $this->offset = 0;
        $this->loadCampaigns($current);
    }

    public function updatedDateTo(CurrentCustomer $current): void
    {
        $this->offset = 0;
        $this->loadCampaigns($current);
    }

    public function refreshCampaigns(CurrentCustomer $current): void
    {
        $this->offset = 0;
        $this->loadCampaigns($current);
        session()->flash('success', 'Kampanjlistan uppdaterad.');
    }

    public function clearFilters(CurrentCustomer $current): void
    {
        $this->reset(['statusFilter', 'searchTerm', 'dateFrom', 'dateTo', 'offset']);
        $this->loadCampaigns($current);
    }

    public function loadMoreCampaigns(CurrentCustomer $current): void
    {
        $this->offset += $this->limit;
        $this->loadCampaigns($current, true);
    }

    private function loadCampaigns(CurrentCustomer $current, bool $append = false): void
    {
        if (!$append) {
            $this->campaigns = [];
        }

        $customer = $current->get();
        abort_unless($customer, 403);

        $site = $customer->sites()->whereKey($current->getSiteId())->first();
        if (!$site || !$site->mailchimp_api_key) {
            return;
        }

        try {
            $key = decrypt($site->mailchimp_api_key);
            $dc = explode('-', $key)[1] ?? config('services.mailchimp.default_dc');
            if (!$dc) return;

            $http = new Client([
                'base_uri' => "https://{$dc}.api.mailchimp.com/3.0/",
                'timeout' => 30,
                'auth' => ['anystring', $key],
            ]);

            $query = [
                'count' => $this->limit,
                'offset' => $this->offset,
                'sort_field' => 'create_time',
                'sort_dir' => 'DESC',
                'fields' => 'campaigns.id,campaigns.web_id,campaigns.type,campaigns.create_time,campaigns.send_time,campaigns.status,campaigns.emails_sent,campaigns.archive_url,campaigns.settings.subject_line,campaigns.settings.preview_text,campaigns.recipients.list_id,campaigns.recipients.list_name,campaigns.report_summary.opens,campaigns.report_summary.clicks,campaigns.report_summary.open_rate,campaigns.report_summary.click_rate,campaigns.unsubscribed'
            ];

            // Apply filters
            if ($this->statusFilter !== '') {
                $query['status'] = $this->statusFilter;
            }

            if ($this->dateFrom) {
                $query['since_create_time'] = $this->dateFrom . 'T00:00:00Z';
            }

            if ($this->dateTo) {
                $query['before_create_time'] = $this->dateTo . 'T23:59:59Z';
            }

            $res = $http->get('campaigns', ['query' => $query]);
            $data = json_decode((string) $res->getBody(), true);
            $campaigns = $data['campaigns'] ?? [];

            // Apply search filter locally if needed
            if ($this->searchTerm) {
                $campaigns = array_filter($campaigns, function ($campaign) {
                    $subject = $campaign['settings']['subject_line'] ?? '';
                    $preview = $campaign['settings']['preview_text'] ?? '';
                    $searchIn = strtolower($subject . ' ' . $preview);
                    return str_contains($searchIn, strtolower($this->searchTerm));
                });
            }

            if ($append) {
                $this->campaigns = array_merge($this->campaigns, $campaigns);
            } else {
                $this->campaigns = $campaigns;
            }

        } catch (\Exception $e) {
            \Log::error('MailchimpHistory: Failed to load campaigns', [
                'error' => $e->getMessage(),
                'customer_id' => $customer->id,
                'site_id' => $site->id,
            ]);

            if (!$append) {
                session()->flash('error', 'Kunde inte hämta kampanjer från Mailchimp. Kontrollera dina API-inställningar.');
            }
        }
    }

    public function render()
    {
        return view('livewire.marketing.mailchimp-history');
    }
}
