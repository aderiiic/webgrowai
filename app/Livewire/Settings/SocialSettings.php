<?php

namespace App\Livewire\Settings;

use App\Models\SocialIntegration;
use App\Support\CurrentCustomer;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class SocialSettings extends Component
{
    public ?int $customerId = null;

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

    public string $li_owner_urn = '';   // t.ex. urn:li:person:... eller urn:li:organization:...
    public string $li_access_token = ''; // tomt om redan finns
    public ?string $li_status = null;
    public ?string $li_message = null;

    public function mount(CurrentCustomer $current): void
    {
        $customer = $current->get();
        abort_unless($customer, 403);
        $this->customerId = $customer->id;

        $fb = SocialIntegration::where('customer_id', $customer->id)->where('provider','facebook')->first();
        if ($fb) {
            $this->fb_page_id = (string) ($fb->page_id ?? '');
            // Visa inte token – låt fältet vara tomt om vi redan har ett
            $this->fb_status = $fb->status;
        }

        $ig = SocialIntegration::where('customer_id', $customer->id)->where('provider','instagram')->first();
        if ($ig) {
            $this->ig_user_id = (string) ($ig->ig_user_id ?? '');
            $this->ig_status = $ig->status;
        }

        if ($li = SocialIntegration::where('customer_id', $customer->id)->where('provider','linkedin')->first()) {
            $this->li_owner_urn = (string) ($li->page_id ?? ''); // owner URN
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
            'customer_id' => $this->customerId,
            'provider'    => 'facebook',
        ]);
        $rec->page_id = $this->fb_page_id;

        if ($this->fb_access_token !== '') {
            $rec->access_token = $this->fb_access_token;
        } elseif (!$rec->exists) {
            $this->addError('fb_access_token', 'Access token krävs första gången.');
            return;
        }

        $rec->status = 'active';
        $rec->save();

        $this->fb_status = $rec->status;
        $this->fb_message = 'Facebook-inställningar sparade.';
        session()->flash('success', 'Facebook sparad.');
    }

    public function testFacebook(): void
    {
        $this->fb_message = null;
        try {
            $rec = SocialIntegration::where('customer_id', $this->customerId)->where('provider','facebook')->firstOrFail();
            $http = new Client(['base_uri' => 'https://graph.facebook.com/v19.0/', 'timeout' => 20]);
            // Hämta enkel info om sidan – kräver PageID + token med rätt scopes
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
            'customer_id' => $this->customerId,
            'provider'    => 'instagram',
        ]);
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
        $this->ig_message = 'Instagram-inställningar sparade.';
        session()->flash('success', 'Instagram sparad.');
    }

    public function testInstagram(): void
    {
        $this->ig_message = null;
        try {
            $rec = SocialIntegration::where('customer_id', $this->customerId)->where('provider','instagram')->firstOrFail();
            $http = new Client(['base_uri' => 'https://graph.facebook.com/v19.0/', 'timeout' => 20]);
            // Hämta IG-användare (Business) med username som enkel test
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
            'customer_id' => $this->customerId,
            'provider'    => 'linkedin',
        ]);
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
        $this->li_message = 'LinkedIn-inställningar sparade.';
        session()->flash('success', 'LinkedIn sparad.');
    }

    public function testLinkedIn(): void
    {
        $this->li_message = null;
        try {
            $rec = SocialIntegration::where('customer_id', $this->customerId)->where('provider','linkedin')->firstOrFail();
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
                // Person via OIDC userinfo (matchar openid/profile/email)
                $res = $http->get('v2/userinfo', [
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

    public function render()
    {
        return view('livewire.settings.social-settings');
    }
}
