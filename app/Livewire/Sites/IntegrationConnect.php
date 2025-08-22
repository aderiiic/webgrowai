<?php

namespace App\Livewire\Sites;

use App\Models\Integration;
use App\Models\Site;
use App\Services\Sites\IntegrationManager;
use Illuminate\Support\Facades\Crypt;
use Livewire\Component;

class IntegrationConnect extends Component
{
    public Site $site;

    public string $provider = 'wordpress'; // wordpress|shopify|custom

    // WordPress
    public string $wp_url = '';
    public string $wp_username = '';
    public string $wp_app_password = '';

    // Shopify
    public string $shop_domain = '';
    public string $shop_access_token = '';

    // Custom
    public string $custom_mode = 'crawler'; // crawler|api
    public string $custom_sitemap_url = '';
    public string $custom_api_base = '';
    public string $custom_api_key = '';
    public string $custom_endpoint_list = '';
    public string $custom_endpoint_get = '';

    public ?string $status = null;
    public ?string $last_error = null;

    public array $diag = [];

    public function mount(Site $site): void
    {
        $this->site = $site->loadMissing('customer');

        $user = auth()->user();
        if (!$user->isAdmin()) {
            abort_unless($user->customers()->whereKey($site->customer_id)->exists(), 403);
        }

        $existing = Integration::where('site_id', $site->id)->first();
        if ($existing) {
            $this->provider = $existing->provider;
            $this->status = $existing->status;
            $this->last_error = $existing->last_error;
            $creds = $existing->credentials ?? [];
            if ($existing->provider === 'wordpress') {
                $this->wp_url = (string)($creds['wp_url'] ?? '');
                $this->wp_username = (string)($creds['wp_username'] ?? '');
            } elseif ($existing->provider === 'shopify') {
                $this->shop_domain = (string)($creds['shop_domain'] ?? '');
            } else {
                $this->custom_mode = (string)($creds['mode'] ?? 'crawler');
                $this->custom_sitemap_url = (string)($creds['sitemap_url'] ?? '');
                $this->custom_api_base = (string)($creds['base_url'] ?? '');
                $this->custom_endpoint_list = (string)($creds['endpoints']['list_url'] ?? '');
                $this->custom_endpoint_get = (string)($creds['endpoints']['get_url'] ?? '');
            }
        }
    }

    public function save(IntegrationManager $manager): void
    {
        $rules = [
            'provider' => 'required|in:wordpress,shopify,custom',
        ];

        if ($this->provider === 'wordpress') {
            $rules += [
                'wp_url' => 'required|url',
                'wp_username' => 'required|string',
                'wp_app_password' => 'nullable|string',
            ];
        } elseif ($this->provider === 'shopify') {
            $rules += [
                'shop_domain' => 'required|string', // t.ex. my-shop.myshopify.com
                'shop_access_token' => 'nullable|string',
            ];
        } else {
            $rules += [
                'custom_mode' => 'required|in:crawler,api',
                'custom_sitemap_url' => 'required_if:custom_mode,crawler|nullable|url',
                'custom_api_base' => 'required_if:custom_mode,api|nullable|url',
                'custom_api_key' => 'nullable|string',
                'custom_endpoint_list' => 'nullable|url',
                'custom_endpoint_get' => 'nullable|url',
            ];
        }

        $this->validate($rules);

        $integration = Integration::firstOrNew([
            'site_id' => $this->site->id,
        ]);
        $integration->provider = $this->provider;

        $credentials = [];
        if ($this->provider === 'wordpress') {
            $credentials = [
                'wp_url' => rtrim($this->wp_url, '/'),
                'wp_username' => $this->wp_username,
            ];
            if ($this->wp_app_password !== '') {
                $credentials['wp_app_password'] = Crypt::encryptString($this->wp_app_password);
            } elseif (!$integration->exists) {
                $this->addError('wp_app_password', 'App‑lösenord krävs vid första anslutningen.');
                return;
            }
        } elseif ($this->provider === 'shopify') {
            $credentials = [
                'shop_domain' => rtrim($this->shop_domain, '/'),
            ];
            if ($this->shop_access_token !== '') {
                $credentials['access_token'] = Crypt::encryptString($this->shop_access_token);
            } elseif (!$integration->exists) {
                // För OAuth-flödet behöver vi inte token här; använd knappen "Anslut med Shopify" istället.
                // Låt bli att stoppa här – användaren kan köra OAuth-knappen utan att spara token manuellt.
            }
        } else {
            $credentials = [
                'mode' => $this->custom_mode,
                'sitemap_url' => $this->custom_sitemap_url ?: null,
                'base_url' => $this->custom_api_base ?: null,
                'api_key' => $this->custom_api_key !== '' ? Crypt::encryptString($this->custom_api_key) : null,
                'endpoints' => [
                    'list_url' => $this->custom_endpoint_list ?: null,
                    'get_url'  => $this->custom_endpoint_get ?: null,
                ],
            ];
        }

        $integration->credentials = $credentials;

        try {
            $integration->status = 'connecting';
            $integration->last_error = null;
            $integration->save();

            $client = $manager->forIntegration($integration);
            $client->listDocuments(['limit' => 1]);

            $integration->status = 'connected';
            $integration->last_error = null;
        } catch (\Throwable $e) {
            $integration->status = 'error';
            $integration->last_error = $e->getMessage();
        }

        $integration->save();

        $this->status = $integration->status;
        $this->last_error = $integration->last_error;

        session()->flash('success', $integration->status === 'connected'
            ? 'Integrationen är ansluten.'
            : 'Misslyckades att ansluta. Kontrollera uppgifterna.');
    }

    // NYTT: Shopify-OAuth från connect-sidan
    public function startShopifyConnect(): void
    {
        $data = $this->validate([
            'shop_domain' => ['required', 'string', 'regex:/^[a-zA-Z0-9][a-zA-Z0-9-]*\.myshopify\.com$/'],
        ], [
            'shop_domain.required' => 'Ange din myshopify.com-domän.',
            'shop_domain.regex'    => 'Ange domänen i formatet my-shop.myshopify.com.',
        ]);

        $url = route('integrations.shopify.embedded', [
            'shop' => $data['shop_domain'],
            'site' => $this->site->id,
        ]);

        $this->redirect($url, navigate: false);
    }

    public function validateConnection(IntegrationManager $manager): void
    {
        $this->diag = [];
        try {
            $integration = Integration::where('site_id', $this->site->id)->firstOrFail();
            $client = $manager->forIntegration($integration);

            $docs = $client->listDocuments(['limit' => 3]);
            $sample = array_map(function ($d) {
                return [
                    'id'    => $d['id'] ?? null,
                    'type'  => $d['type'] ?? null,
                    'title' => $d['title'] ?? null,
                    'url'   => $d['url'] ?? null,
                ];
            }, $docs);

            $this->diag = [
                'ok'     => true,
                'sample' => $sample,
                'ts'     => now()->toDateTimeString(),
            ];
            $this->status = 'connected';
            $this->last_error = null;

            session()->flash('success', 'Anslutningen validerades.');
        } catch (\Throwable $e) {
            $this->diag = [
                'ok'   => false,
                'err'  => $e->getMessage(),
                'ts'   => now()->toDateTimeString(),
            ];
            $this->status = 'error';
            $this->last_error = $e->getMessage();
            session()->flash('success', 'Validering misslyckades. Kontrollera uppgifterna.');
        }
    }

    public function render()
    {
        $integration = Integration::where('site_id', $this->site->id)->first();
        return view('livewire.sites.integration-connect', compact('integration'))
            ->layout('layouts.app');
    }
}
