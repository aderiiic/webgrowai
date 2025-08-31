<?php

namespace App\Livewire\Analytics;

use App\Models\Ga4Integration;
use App\Support\CurrentCustomer;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.app')]
class Ga4Settings extends Component
{
    use WithFileUploads;

    public ?int $siteId = null;

    // Befintliga (service account, valfritt)
    public string $propertyId = '';
    public ?string $serviceJsonText = null;
    public $serviceJsonFile = null;

    // Nya fält för OAuth-listor/val
    public bool $hasOAuth = false;
    public array $properties = []; // [['id'=>'properties/123','displayName'=>'...'], ...]
    public string $selectedProperty = '';
    public array $streams = [];    // [['id'=>'...','displayName'=>'...','type'=>'WEB_DATA_STREAM'], ...]
    public string $selectedStream = '';
    public string $hostname = '';

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
            $this->hasOAuth   = !empty($ga4->access_token);
        }

        // Om OAuth finns men property saknas – ladda lista
        if ($this->hasOAuth && empty($this->propertyId)) {
            $this->loadAccountsAndProperties();
        }
    }

    public function save(): void
    {
        // Detta är kvar för “manuell” inmatning eller service account läge
        $this->validate([
            'propertyId' => ['required','string','max:120'],
            'serviceJsonText' => [Rule::requiredIf(!$this->serviceJsonFile), 'nullable', 'string'],
            'serviceJsonFile' => [Rule::requiredIf(!$this->serviceJsonText), 'nullable', 'file', 'mimes:json', 'max:1024'],
        ]);

        $json = $this->serviceJsonText ?: ($this->serviceJsonFile ? file_get_contents($this->serviceJsonFile->getRealPath()) : null);

        Ga4Integration::updateOrCreate(
            ['site_id' => $this->siteId],
            [
                'property_id' => $this->propertyId,
                'service_account_json' => $json ? encrypt($json) : \DB::raw('service_account_json'),
                'status' => 'connected',
            ]
        );

        session()->flash('success', 'GA4 sparad.');
    }

    public function loadAccountsAndProperties(): void
    {
        $ga = Ga4Integration::where('site_id', $this->siteId)->first();
        if (!$ga || empty($ga->access_token)) {
            $this->hasOAuth = false;
            return;
        }

        $token = decrypt((string) $ga->access_token);

        // 1) Lista konton
        $acc = Http::withToken($token)->get('https://analyticsadmin.googleapis.com/v1beta/accounts');
        if (!$acc->ok()) {
            $this->addError('oauth', 'Kunde inte läsa GA4‑konton. Pröva koppla om GA4.');
            return;
        }

        $accounts = $acc->json('accounts') ?: [];
        $props = [];

        // 2) För varje konto – lista properties
        foreach ($accounts as $a) {
            $name = $a['name'] ?? null; // ex "accounts/12345"
            if (!$name) continue;

            $p = Http::withToken($token)->get("https://analyticsadmin.googleapis.com/v1beta/{$name}/properties", [
                'showDeleted' => 'false',
            ]);
            if ($p->ok()) {
                foreach ((array) ($p->json('properties') ?: []) as $prop) {
                    $props[] = [
                        'id' => $prop['name'] ?? '',
                        'displayName' => $prop['displayName'] ?? ($prop['name'] ?? ''),
                    ];
                }
            }
        }

        usort($props, fn($a,$b) => strcasecmp($a['displayName'], $b['displayName']));
        $this->properties = $props;
    }

    public function updatedSelectedProperty(string $value): void
    {
        $this->streams = [];
        $this->selectedStream = '';
        $this->hostname = '';

        if (empty($value)) return;

        $ga = Ga4Integration::where('site_id', $this->siteId)->first();
        if (!$ga || empty($ga->access_token)) return;

        $token = decrypt((string) $ga->access_token);
        $propPath = str_starts_with($value, 'properties/') ? $value : "properties/{$value}";

        // Hämta datastreams (för att kunna välja web‑stream/hostname)
        $resp = Http::withToken($token)->get("https://analyticsadmin.googleapis.com/v1beta/{$propPath}/dataStreams");
        if (!$resp->ok()) return;

        $streams = [];
        foreach ((array) ($resp->json('dataStreams') ?: []) as $s) {
            $type = $s['type'] ?? '';
            $display = $s['displayName'] ?? $type;
            $id = $s['name'] ?? ''; // ex "properties/123/dataStreams/456"
            $streams[] = ['id' => $id, 'displayName' => $display, 'type' => $type];

            // Plocka hostname om det är web‑stream
            if ($type === 'WEB_DATA_STREAM') {
                $web = $s['webStreamData'] ?? [];
                if (!$this->hostname && !empty($web['defaultUri'])) {
                    $h = parse_url($web['defaultUri'], PHP_URL_HOST);
                    if ($h) $this->hostname = mb_strtolower($h);
                }
            }
        }

        $this->streams = $streams;
    }

    public function saveSelection(): void
    {
        $this->validate([
            'selectedProperty' => ['required', 'string', 'max:120'],
            'selectedStream'   => ['nullable', 'string', 'max:120'],
            'hostname'         => ['nullable', 'string', 'max:190'],
        ]);

        // Skicka till din befintliga controller-rout
        request()->merge([
            'site_id'     => $this->siteId,
            'property_id' => $this->selectedProperty,
            'stream_id'   => $this->selectedStream ?: null,
            'hostname'    => $this->hostname ?: null,
        ]);

        // Call controller via HTTP post (eller uppdatera direkt här om du vill)
        app(\App\Http\Controllers\Analytics\Ga4OAuthController::class)->select(request());

        session()->flash('success', 'GA4‑property vald.');
        // Sätt lokala värden så UI uppdateras
        $this->propertyId = $this->selectedProperty;
    }

    public function render()
    {
        return view('livewire.analytics.ga4-settings', [
            'siteId' => $this->siteId,
        ]);
    }
}
