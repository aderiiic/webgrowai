<?php

namespace App\Livewire\Sites;

use App\Models\Integration;
use App\Models\Site;
use App\Models\WpIntegration;
use App\Services\WordPressClient;
use Illuminate\Support\Facades\Crypt;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class WordPressConnect extends Component
{
    public Site $site;
    public string $wp_url = '';
    public string $wp_username = '';
    public string $wp_app_password = '';
    public ?string $status = null;
    public ?string $last_error = null;

    public function mount(Site $site): void
    {
        $this->site = $site->loadMissing('customer');
        // Auktorisera: admin eller tillhör aktiv kund
        $user = auth()->user();
        if (!$user->isAdmin()) {
            abort_unless($user->customers()->whereKey($site->customer_id)->exists(), 403);
        }

        // Läs endast från Integration (enda sanningen)
        $int = \App\Models\Integration::where('site_id', $site->id)
            ->where('provider', 'wordpress')
            ->first();

        if ($int) {
            $creds = (array) ($int->credentials ?? []);
            $this->wp_url = rtrim((string)($creds['wp_url'] ?? $creds['url'] ?? ''), '/');
            $this->wp_username = (string)($creds['wp_username'] ?? $creds['username'] ?? '');
            // Visa status/fel från Integration
            $this->status = $int->status;
            $this->last_error = $int->last_error;
        } else {
            // Inget Integration‑record ännu: lämna tomt i form
            $this->wp_url = '';
            $this->wp_username = '';
            $this->status = null;
            $this->last_error = null;
        }
    }

    public function save(): void
    {
        $this->validate([
            'wp_url' => 'required|url',
            'wp_username' => 'required|string',
            'wp_app_password' => 'nullable|string',
        ]);

        // 1) Spara/uppdatera WpIntegration (legacy)
        $wp = WpIntegration::firstOrNew(['site_id' => $this->site->id]);
        $wp->wp_url = rtrim($this->wp_url, '/');
        $wp->wp_username = $this->wp_username;

        $newPassProvided = $this->wp_app_password !== '';
        if ($newPassProvided) {
            $enc = Crypt::encryptString(trim($this->wp_app_password));
            $wp->wp_app_password = $enc;
        } elseif (!$wp->exists) {
            $this->addError('wp_app_password', 'App-lösenord krävs vid första anslutningen.');
            return;
        }

        // 2) Synka till Integration (provider=wordpress) som används av publiceringen
        $int = Integration::firstOrNew([
            'site_id'  => $this->site->id,
            'provider' => 'wordpress',
        ]);

        $creds = (array) ($int->credentials ?? []);
        // Normalisera nycklar
        $creds['wp_url'] = rtrim($this->wp_url, '/');
        $creds['wp_username'] = $this->wp_username;

        // Lagra samma "format" som WpIntegration: krypterat strängvärde
        if ($newPassProvided) {
            $creds['wp_app_password'] = Crypt::encryptString(trim($this->wp_app_password));
        } else {
            // Om redan finns i Integration – behåll befintligt. Annars ta från WpIntegration.
            if (!isset($creds['wp_app_password']) && $wp->wp_app_password) {
                $creds['wp_app_password'] = $wp->wp_app_password;
            }
        }

        $int->credentials = $creds;
        if (!$int->exists) {
            $int->name = 'WordPress';
            $int->status = 'connected';
        }

        // 3) Testa anslutning via WordPressClient::for(WpIntegration) (samma som UI-test)
        try {
            $client = WordPressClient::for($wp);
            $test = $client->testConnection();

            if (!($test['ok'] ?? false)) {
                $wp->status = 'error';
                $wp->last_error = trim(($test['message'] ?? 'Kunde inte ansluta.').' '.($test['hint'] ?? ''));
                $int->status = 'error';
                $int->last_error = $wp->last_error;
            } else {
                $wp->status = 'connected';
                $wp->last_error = null;
                $int->status = 'connected';
                $int->last_error = null;
            }
        } catch (\Throwable $e) {
            $wp->status = 'error';
            $wp->last_error = $e->getMessage();
            $int->status = 'error';
            $int->last_error = $e->getMessage();
        }

        // 4) Spara båda posterna
        $wp->save();
        $int->save();

        // 5) Uppdatera lokalt UI‑state
        $this->status = $wp->status;
        $this->last_error = $wp->last_error;

        session()->flash('success', $wp->status === 'connected'
            ? 'WordPress anslutet och synkat.'
            : 'Misslyckades att ansluta. Kontrollera uppgifterna.');
    }

    public function test(): void
    {
        $integration = WpIntegration::where('site_id', $this->site->id)->first();
        if (!$integration) {
            $this->addError('wp_url', 'Spara uppgifterna först.');
            return;
        }
        try {
            $client = WordPressClient::for($integration);
            $test = $client->testConnection();

            if (!($test['ok'] ?? false)) {
                $this->status = 'error';
                $this->last_error = trim(($test['message'] ?? 'Kunde inte ansluta.').' '.($test['hint'] ?? ''));
                session()->flash('success', 'Misslyckades att ansluta – se felmeddelande nedan.');
            } else {
                $this->status = 'connected';
                $this->last_error = null;
                session()->flash('success', 'Anslutning OK. Användare: '.((string)($test['user']['name'] ?? 'okänd')));
            }
        } catch (\Throwable $e) {
            $this->status = 'error';
            $this->last_error = $e->getMessage();
            session()->flash('success', 'Misslyckades att ansluta – se felmeddelande nedan.');
        }
    }

    public function render()
    {
        $integration = WpIntegration::where('site_id', $this->site->id)->first();
        return view('livewire.sites.word-press-connect', compact('integration'));
    }
}
