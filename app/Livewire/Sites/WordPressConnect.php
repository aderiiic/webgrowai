<?php

namespace App\Livewire\Sites;

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

        $integration = WpIntegration::where('site_id', $site->id)->first();
        if ($integration) {
            $this->wp_url = $integration->wp_url;
            $this->wp_username = $integration->wp_username;
            $this->status = $integration->status;
            $this->last_error = $integration->last_error;
        }
    }

    public function save(): void
    {
        $this->validate([
            'wp_url' => 'required|url',
            'wp_username' => 'required|string',
            'wp_app_password' => 'nullable|string', // tomt = behåll tidigare
        ]);

        $integration = WpIntegration::firstOrNew(['site_id' => $this->site->id]);
        $integration->wp_url = rtrim($this->wp_url, '/');
        $integration->wp_username = $this->wp_username;

        if ($this->wp_app_password !== '') {
            $integration->wp_app_password = Crypt::encryptString($this->wp_app_password);
        } elseif (!$integration->exists) {
            $this->addError('wp_app_password', 'App-lösenord krävs vid första anslutningen.');
            return;
        }

        // Testa anslutning genom att hämta en sida posts
        try {
            $client = WordPressClient::for($integration);
            $client->getPosts(['per_page' => 1]);
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
            ? 'WordPress anslutet.'
            : 'Misslyckades att ansluta. Kontrollera uppgifterna.');
    }

    public function render()
    {
        $integration = WpIntegration::where('site_id', $this->site->id)->first();
        return view('livewire.sites.word-press-connect', compact('integration'));
    }
}
