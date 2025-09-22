<?php

namespace App\Livewire\Settings;

use App\Models\SocialIntegration;
use App\Support\CurrentCustomer;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class SocialSettings extends Component
{
    public ?int $customerId = null;
    public ?int $siteId = null;

    // Facebook
    public string $fb_page_id = '';
    public string $fb_access_token = '';
    public ?string $fb_status = null;
    public ?string $fb_message = null;

    // Instagram
    public string $ig_user_id = '';
    public string $ig_access_token = '';
    public ?string $ig_status = null;
    public ?string $ig_message = null;

    // LinkedIn
    public string $li_owner_urn = '';
    public string $li_access_token = '';
    public ?string $li_status = null;
    public ?string $li_message = null;

    public function mount(CurrentCustomer $current): void
    {
        $customer = $current->get();
        abort_unless($customer, 403);

        $this->customerId = $customer->id;
        $this->siteId = $current->getSiteId();
        $site = $customer->sites()->whereKey($this->siteId)->first();
        abort_unless($site, 404);

        $fb = SocialIntegration::where('site_id', $this->siteId)->where('provider','facebook')->first();
        if ($fb) {
            $this->fb_page_id = (string) ($fb->page_id ?? '');
            $this->fb_status = $fb->status;
        }

        $ig = SocialIntegration::where('site_id', $this->siteId)->where('provider','instagram')->first();
        if ($ig) {
            $this->ig_user_id = (string) ($ig->ig_user_id ?? '');
            $this->ig_status = $ig->status;
        }

        $li = SocialIntegration::where('site_id', $this->siteId)->where('provider','linkedin')->first();
        if ($li) {
            $this->li_owner_urn = (string) ($li->page_id ?? '');
            $this->li_status = $li->status;
        }
    }

    public function saveFacebook(): void
    {
        $this->validate([
            'fb_page_id' => 'required|string',
            'fb_access_token' => 'nullable|string',
        ]);

        $rec = SocialIntegration::firstOrNew([
            'site_id'   => $this->siteId,
            'provider'  => 'facebook',
        ], [
            'customer_id' => $this->customerId,
        ]);

        $rec->customer_id = $this->customerId;
        $rec->page_id = $this->fb_page_id;

        if ($this->fb_access_token !== '') {
            $rec->access_token = $this->fb_access_token;
        } elseif (!$rec->exists) {
            $this->addError('fb_access_token', 'Access token krävs första gången.');
            return;
        }

        try {
            $pageToken = $this->getPageAccessToken($rec->page_id, $rec->access_token);
            if ($pageToken) {
                $rec->access_token = $pageToken;
            }
        } catch (\Throwable $e) {
            // Fortsätt med befintlig token om vi inte får sidtoken
        }

        $rec->status = 'active';
        $rec->save();

        $this->fb_status = $rec->status;
        $this->fb_message = 'Facebook-inställningar sparade för denna sajt.';
        session()->flash('success', 'Facebook sparad.');

        // NYTT: om sidan har kopplat IG‑konto – anslut Instagram automatiskt för samma sajt
        try {
            $this->ensureInstagramFromFacebook($rec->page_id, $rec->access_token);
        } catch (\Throwable $e) {
            // mjuk felhantering – visa bara info
            $this->ig_message = 'Info: Kunde inte auto‑ansluta Instagram från Facebook ('.$e->getMessage().')';
        }
    }

    public function testFacebook(): void
    {
        $this->fb_message = null;
        try {
            $rec = SocialIntegration::where('site_id', $this->siteId)->where('provider','facebook')->firstOrFail();
            $http = new Client(['base_uri' => 'https://graph.facebook.com/v19.0/', 'timeout' => 20]);
            $res = $http->get($rec->page_id, [
                'query' => ['access_token' => $rec->access_token, 'fields' => 'id,name'],
            ]);
            $data = json_decode((string) $res->getBody(), true);
            $this->fb_message = 'OK: '.$data['name'].' ('.$data['id'].')';
            $this->fb_status = 'active';
        } catch (\Throwable $e) {
            $this->fb_message = 'Fel: '.$e->getMessage();
            $this->fb_status = 'error';
        }
    }

    public function saveInstagram(): void
    {
        $this->validate([
            'ig_user_id' => 'required|string',
            'ig_access_token' => 'nullable|string',
        ]);

        $rec = SocialIntegration::firstOrNew([
            'site_id'  => $this->siteId,
            'provider' => 'instagram',
        ], [
            'customer_id' => $this->customerId,
        ]);

        $rec->customer_id = $this->customerId;
        $rec->ig_user_id = $this->ig_user_id;

        if ($this->ig_access_token !== '') {
            $rec->access_token = $this->ig_access_token;
        } elseif (!$rec->exists) {
            $this->addError('ig_access_token', 'Access token krävs första gången.');
            return;
        }

        $rec->status = 'active';
        $rec->save();

        $this->ig_status = $rec->status;
        $this->ig_message = 'Instagram-inställningar sparade för denna sajt.';
        session()->flash('success', 'Instagram sparad.');

        // NYTT: om IG‑kontot har kopplad Facebook Page – anslut Facebook automatiskt för samma sajt
        try {
            $this->ensureFacebookFromInstagram($rec->ig_user_id, $rec->access_token);
        } catch (\Throwable $e) {
            $this->fb_message = 'Info: Kunde inte auto‑ansluta Facebook från Instagram ('.$e->getMessage().')';
        }
    }

    public function testInstagram(): void
    {
        $this->ig_message = null;
        try {
            $rec = SocialIntegration::where('site_id', $this->siteId)->where('provider','instagram')->firstOrFail();
            $http = new Client(['base_uri' => 'https://graph.facebook.com/v19.0/', 'timeout' => 20]);
            $res = $http->get($rec->ig_user_id, [
                'query' => ['access_token' => $rec->access_token, 'fields' => 'id,username'],
            ]);
            $data = json_decode((string) $res->getBody(), true);
            $this->ig_message = 'OK: '.$data['username'].' ('.$data['id'].')';
            $this->ig_status = 'active';
        } catch (\Throwable $e) {
            $this->ig_message = 'Fel: '.$e->getMessage();
            $this->ig_status = 'error';
        }
    }

    public function saveLinkedIn(): void
    {
        $this->validate([
            'li_owner_urn' => 'required|string',
            'li_access_token' => 'nullable|string',
        ]);

        $rec = SocialIntegration::firstOrNew([
            'site_id'  => $this->siteId,
            'provider' => 'linkedin',
        ], [
            'customer_id' => $this->customerId,
        ]);

        $rec->customer_id = $this->customerId;
        $rec->page_id = $this->li_owner_urn;

        if ($this->li_access_token !== '') {
            $rec->access_token = $this->li_access_token;
        } elseif (!$rec->exists) {
            $this->addError('li_access_token', 'Access token krävs första gången.');
            return;
        }

        $rec->status = 'active';
        $rec->save();

        $this->li_status = $rec->status;
        $this->li_message = 'LinkedIn-inställningar sparade för denna sajt.';
        session()->flash('success', 'LinkedIn sparad.');
    }

    public function testLinkedIn(): void
    {
        $this->li_message = null;
        try {
            $rec = SocialIntegration::where('site_id', $this->siteId)->where('provider','linkedin')->firstOrFail();
            $http = new Client(['base_uri' => 'https://api.linkedin.com/', 'timeout' => 20]);

            $owner = (string) ($rec->page_id ?? '');
            $token = $rec->access_token;

            if (str_starts_with($owner, 'urn:li:organization:')) {
                $orgId = substr($owner, strlen('urn:li:organization:'));
                $res = $http->get("v2/organizations/{$orgId}", [
                    'headers' => ['Authorization' => "Bearer {$token}"],
                    'query' => ['projection' => '(localizedName)'],
                ]);
                $data = json_decode((string) $res->getBody(), true);
                $name = $data['localizedName'] ?? $orgId;
                $this->li_message = 'OK: ' . $name . ' (' . $orgId . ')';
            } else {
                $res = $http->get('v2/me', [
                    'headers' => ['Authorization' => "Bearer {$token}"],
                ]);
                $data = json_decode((string) $res->getBody(), true);
                $sub = $data['sub'] ?? 'unknown';
                $this->li_message = 'OK: Person (' . $sub . ')';
            }

            $this->li_status = 'active';

        } catch (\Throwable $e) {
            $this->li_message = 'Fel: ' . $e->getMessage();
            $this->li_status = 'error';
        }
    }

    // -----------------------------------------------------------------------------------
    // Auto‑koppling mellan Facebook Page <-> Instagram Business Account för samma sajt
    // -----------------------------------------------------------------------------------
    private function http(): Client
    {
        return new Client(['base_uri' => 'https://graph.facebook.com/v19.0/', 'timeout' => 20]);
    }

    private function getPageAccessToken(string $pageId, string $token): ?string
    {
        try {
            $res = $this->http()->get($pageId, [
                'query' => ['access_token' => $token, 'fields' => 'access_token'],
            ]);
            $data = json_decode((string)$res->getBody(), true);
            return !empty($data['access_token']) ? (string)$data['access_token'] : null;
        } catch (\Throwable $e) {
            Log::warning('[FB] Kunde inte hämta Page Access Token', ['page_id' => $pageId, 'err' => $e->getMessage()]);
            return null;
        }
    }

    private function ensureInstagramFromFacebook(string $pageId, string $token): void
    {
        $http = $this->http();

        $tryEdges = [
            ['fields' => 'instagram_business_account{id,username}', 'path' => $pageId, 'key' => 'instagram_business_account'],
            ['fields' => 'connected_instagram_account{id,username}', 'path' => $pageId, 'key' => 'connected_instagram_account'],
            ['fields' => null, 'path' => $pageId.'/instagram_accounts', 'key' => 'data'],
        ];

        $found = null;
        foreach ($tryEdges as $try) {
            try {
                $query = ['access_token' => $token];
                if ($try['fields']) {
                    $query['fields'] = $try['fields'];
                } else {
                    $query['fields'] = 'id,username';
                    $query['limit']  = 1;
                }

                $res  = $http->get($try['path'], ['query' => $query]);
                $data = json_decode((string)$res->getBody(), true);

                Log::info('[FB] IG lookup', [
                    'site_id' => $this->siteId,
                    'edge'    => $try['path'],
                    'status'  => $res->getStatusCode(),
                    'has_key' => array_key_exists($try['key'], $data),
                ]);

                if ($try['key'] === 'data') {
                    $candidate = $data['data'][0] ?? null;
                    if (!empty($candidate['id'])) {
                        $found = $candidate;
                        break;
                    }
                } else {
                    $candidate = $data[$try['key']] ?? null;
                    if (!empty($candidate['id'])) {
                        $found = $candidate;
                        break;
                    }
                }
            } catch (\Throwable $e) {
                Log::warning('[FB] IG lookup fel', [
                    'site_id' => $this->siteId,
                    'edge'    => $try['path'],
                    'err'     => $e->getMessage(),
                ]);
                // pröva nästa edge
            }
        }

        if (!$found || empty($found['id'])) {
            // Ingen IG hittad – ge tydligt meddelande i UI
            $this->ig_message = 'Hittade inget kopplat Instagram-konto på vald Facebook-sida. Kontrollera att:
- Sidan är kopplad till ett Instagram Business/Creator-konto i Meta (Linked Accounts).
- Du gav appen åtkomst till just denna sida och IG vid inloggningen.
- Appen har rätt scopes (t.ex. pages_show_list, pages_manage_metadata, pages_read_engagement, instagram_basic).';
            return;
        }

        $igUserId = (string) $found['id'];

        $insta = SocialIntegration::firstOrNew([
            'site_id'  => $this->siteId,
            'provider' => 'instagram',
        ], [
            'customer_id' => $this->customerId,
        ]);

        $insta->customer_id = $this->customerId;
        $insta->ig_user_id  = $igUserId;

        // Använd befintligt IG-token om finns, annars återanvänd sidtoken
        if ($this->ig_access_token !== '') {
            $insta->access_token = $this->ig_access_token;
        } elseif ($insta->exists && !empty($insta->access_token)) {
            // behåll befintlig IG‑token
        } else {
            $insta->access_token = $token;
        }

        $insta->status = 'active';
        $insta->save();

        $this->ig_user_id = $insta->ig_user_id;
        $this->ig_status  = $insta->status;
        $this->ig_message = 'Instagram auto‑anslutet via vald Facebook‑sida.';
    }

    private function ensureFacebookFromInstagram(string $igUserId, string $token): void
    {
        // Best effort: hämta connected_page
        try {
            $res = $this->http()->get($igUserId, [
                'query' => ['access_token' => $token, 'fields' => 'connected_page,username'],
            ]);
            $data = json_decode((string)$res->getBody(), true);
            $page = $data['connected_page'] ?? null;

            if (!$page || empty($page['id'])) {
                return;
            }

            $pageId = (string) $page['id'];

            // Hämta Page Access Token för stabilare API‑anrop framåt
            $pageToken = null;
            try {
                $pageToken = $this->getPageAccessToken($pageId, $token) ?: $token;
            } catch (\Throwable $e) {
                $pageToken = $token;
            }

            $fb = SocialIntegration::firstOrNew([
                'site_id'  => $this->siteId,
                'provider' => 'facebook',
            ], [
                'customer_id' => $this->customerId,
            ]);

            $fb->customer_id = $this->customerId;
            $fb->page_id     = $pageId;

            if ($this->fb_access_token !== '') {
                $fb->access_token = $this->fb_access_token;
            } elseif ($fb->exists && !empty($fb->access_token)) {
                // behåll
            } else {
                $fb->access_token = $pageToken;
            }

            $fb->status = 'active';
            $fb->save();

            $this->fb_page_id = $fb->page_id;
            $this->fb_status  = $fb->status;
            $this->fb_message = 'Facebook auto‑anslutet via valt Instagram‑konto.';
        } catch (\Throwable $e) {
            // tyst
        }
    }

    public function render()
    {
        return view('livewire.settings.social-settings');
    }
}
