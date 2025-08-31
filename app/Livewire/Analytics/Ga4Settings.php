<?php

namespace App\Livewire\Analytics;

use App\Http\Controllers\Analytics\Ga4OAuthController;
use App\Models\Ga4Integration;
use App\Support\CurrentCustomer;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;
use League\OAuth2\Client\Provider\Google as GoogleProvider;

#[Layout('layouts.app')]
class Ga4Settings extends Component
{
    use WithFileUploads;

    public ?int $siteId = null;

    // Manuell property-id (krävs när Admin API ej finns)
    public string $propertyId = '';
    public ?string $serviceJsonText = null;
    public $serviceJsonFile = null;

    // OAuth status
    public bool $hasOAuth = false;

    // Hostname-förslag från Data API
    public array $hostnames = [];   // ['www.exempel.se', 'shop.exempel.se', ...]
    public string $hostname = '';
    public string $validationMessage = '';

    private function provider(): GoogleProvider
    {
        return new GoogleProvider([
            'clientId'     => (string) config('services.google.client_id'),
            'clientSecret' => (string) config('services.google.client_secret'),
            'redirectUri'  => route('analytics.ga4.callback'),
        ]);
    }

    private function getValidToken(?Ga4Integration $ga): ?string
    {
        if (!$ga) return null;
        $access = $ga->access_token ? decrypt((string) $ga->access_token) : null;

        if (!empty($access) && $ga->expires_at && now()->lt($ga->expires_at)) {
            return $access;
        }

        if (empty($ga->refresh_token)) {
            return $access;
        }

        try {
            $provider = $this->provider();
            $newToken = $provider->getAccessToken('refresh_token', [
                'refresh_token' => decrypt((string) $ga->refresh_token),
            ]);

            $newAccess = (string) $newToken->getToken();
            $expRaw    = $newToken->getExpires();
            $expiresAt = now()->addSeconds(is_numeric($expRaw) ? max(300, (int) $expRaw) : 3600);

            $ga->update([
                'access_token' => $newAccess ? encrypt($newAccess) : $ga->access_token,
                'expires_at'   => $expiresAt,
            ]);

            return $newAccess ?: $access;
        } catch (\Throwable $e) {
            $this->addError('oauth', 'Kunde inte förnya Google‑token. Koppla om GA4.');
            return $access;
        }
    }

    public function mount(CurrentCustomer $current): void
    {
        $customer = $current->get();
        abort_unless($customer, 403);

        $sid = (int) ($current->getSiteId() ?: 0);
        abort_unless($sid > 0 && $customer->sites()->whereKey($sid)->exists(), 404, 'Välj en sajt i topbaren.');

        $this->siteId = $sid;

        $ga4 = Ga4Integration::where('site_id', $sid)->first();

        if ($ga4) {
            $this->propertyId = (string) ($ga4->property_id ?? '');
            $this->hostname   = (string) ($ga4->hostname ?? '');
            $this->hasOAuth   = !empty($ga4->access_token) || !empty($ga4->refresh_token);
        }
    }

    // Validera Property ID mot Data API och hämta hostnames att välja
    public function validateProperty(): void
    {
        $this->resetErrorBag('oauth');
        $this->validationMessage = '';
        $this->hostnames = [];

        $ga = Ga4Integration::where('site_id', $this->siteId)->first();
        if (!$ga) {
            $this->addError('oauth', 'Ingen GA4‑koppling hittades. Koppla GA4 först.');
            return;
        }

        $token = $this->getValidToken($ga);
        if (!$token) {
            $this->addError('oauth', 'Ogiltig token. Koppla GA4 igen.');
            return;
        }

        $propPath = str_starts_with($this->propertyId, 'properties/')
            ? $this->propertyId
            : ('properties/' . $this->propertyId);

        // Hämta hostnames via Data API (dimension hostName)
        $body = [
            'dateRanges' => [[
                'startDate' => now()->subDays(28)->toDateString(),
                'endDate'   => 'today',
            ]],
            'dimensions' => [
                ['name' => 'hostName'],
            ],
            'metrics'    => [
                ['name' => 'screenPageViews'],
            ],
            'orderBys'   => [[
                'metric' => ['metricName' => 'screenPageViews'],
                'desc'   => true,
            ]],
            'limit'      => 50,
        ];

        $resp = Http::withToken($token)
            ->post("https://analyticsdata.googleapis.com/v1beta/{$propPath}:runReport", $body);

        if (!$resp->ok()) {
            $code = $resp->status();
            $msg  = $resp->json('error.message') ?: $resp->body();
            $hint = $code === 403 ? ' (saknar läsrättighet till property eller fel Property ID)' : '';
            $this->addError('oauth', "Kunde inte validera property{$hint}. {$msg}");
            return;
        }

        $rows = (array) ($resp->json('rows') ?? []);
        $hosts = [];
        foreach ($rows as $r) {
            $h = $r['dimensionValues'][0]['value'] ?? null;
            if ($h) $hosts[] = mb_strtolower($h);
        }
        $hosts = array_values(array_unique($hosts));

        if (empty($hosts)) {
            $this->validationMessage = 'Property hittad, men inga hostnames kunde läsas (kan bero på ingen trafik senaste 28 dagar). Du kan ange hostname manuellt.';
        } else {
            $this->validationMessage = 'Property validerad. Välj hostname nedan (valfritt).';
            $this->hostnames = $hosts;
            if (empty($this->hostname) && !empty($hosts)) {
                $this->hostname = $hosts[0]; // välj vanligaste som default
            }
        }
    }

    public function saveSelection(): void
    {
        $this->validate([
            'propertyId' => ['required', 'string', 'max:120'],
            'hostname'   => ['nullable', 'string', 'max:190'],
        ]);

        request()->merge([
            'site_id'     => $this->siteId,
            'property_id' => str_starts_with($this->propertyId, 'properties/') ? $this->propertyId : ('properties/' . $this->propertyId),
            'stream_id'   => null,
            'hostname'    => $this->hostname ?: null,
        ]);

        app(Ga4OAuthController::class)->select(request());

        session()->flash('success', 'GA4‑property vald.');
    }

    // Fallback (behåll om du även stödjer service account)
    public function save(): void
    {
        $this->validate([
            'propertyId' => ['required','string','max:120'],
            'serviceJsonText' => [Rule::requiredIf(!$this->serviceJsonFile), 'nullable', 'string'],
            'serviceJsonFile' => [Rule::requiredIf(!$this->serviceJsonText), 'nullable', 'file', 'mimes:json', 'max:1024'],
        ]);

        $json = $this->serviceJsonText ?: ($this->serviceJsonFile ? file_get_contents($this->serviceJsonFile->getRealPath()) : null);

        Ga4Integration::updateOrCreate(
            ['site_id' => $this->siteId],
            [
                'property_id' => str_starts_with($this->propertyId, 'properties/') ? $this->propertyId : ('properties/' . $this->propertyId),
                'service_account_json' => $json ? encrypt($json) : \DB::raw('service_account_json'),
                'status' => 'connected',
            ]
        );

        session()->flash('success', 'GA4 sparad.');
    }

    public function render()
    {
        return view('livewire.analytics.ga4-settings', [
            'siteId' => $this->siteId,
        ]);
    }
}
