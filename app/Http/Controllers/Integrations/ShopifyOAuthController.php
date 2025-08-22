<?php

namespace App\Http\Controllers\Integrations;

use App\Models\Integration;
use App\Models\Site;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class ShopifyOAuthController extends Controller
{
    public function install(Request $request)
    {
        $request->validate([
            'site' => 'required|integer|exists:sites,id',
            'shop' => 'required|string', // my-shop.myshopify.com
        ]);

        $site = Site::findOrFail((int)$request->integer('site'));
        // Auktorisera att användaren får hantera denna site
        $user = $request->user();
        if (!$user->isAdmin()) {
            abort_unless($user->customers()->whereKey($site->customer_id)->exists(), 403);
        }

        $shop        = trim($request->string('shop'));
        $clientId    = config('services.shopify.client_id');
        $scopes      = str_replace(' ', '', (string) config('services.shopify.scopes', 'read_content'));
        $redirectUrl = config('services.shopify.redirect') ?: route('integrations.shopify.callback');

        // CSRF/state
        $state = Str::random(40);
        $request->session()->put('shopify_oauth_state', $state);
        $request->session()->put('shopify_oauth_site', $site->id);

        $authUrl = sprintf(
            'https://%s/admin/oauth/authorize?client_id=%s&scope=%s&redirect_uri=%s&state=%s',
            $shop,
            urlencode($clientId),
            urlencode($scopes),
            urlencode($redirectUrl),
            urlencode($state)
        );

        return redirect()->away($authUrl);
    }

    public function callback(Request $request)
    {
        $shop     = $request->query('shop');
        $code     = $request->query('code');
        $state    = $request->query('state');
        $hmac     = $request->query('hmac');

        // Grundvalideringar
        if (!$shop || !$code || !$state || !$hmac) {
            return $this->failBack('Ogiltig callback-data.');
        }

        // Validera state
        $expectedState = $request->session()->pull('shopify_oauth_state');
        $siteId        = (int) $request->session()->pull('shopify_oauth_site');
        if (!$expectedState || !hash_equals($expectedState, $state) || !$siteId) {
            return $this->failBack('Ogiltigt state.');
        }

        // Validera HMAC (Shopifys signatur)
        $params = $request->query();
        unset($params['signature'], $params['hmac']);
        ksort($params);
        $computed = hash_hmac('sha256', http_build_query($params), config('services.shopify.client_secret'));
        if (!hash_equals($computed, $hmac)) {
            return $this->failBack('HMAC-validering misslyckades.');
        }

        // Byt code mot access token
        $tokenRes = Http::asForm()->post("https://{$shop}/admin/oauth/access_token", [
            'client_id'     => config('services.shopify.client_id'),
            'client_secret' => config('services.shopify.client_secret'),
            'code'          => $code,
        ]);

        if (!$tokenRes->ok()) {
            return $this->failBack('Kunde inte hämta access token.');
        }

        $data = $tokenRes->json();
        $accessToken = $data['access_token'] ?? null;
        if (!$accessToken) {
            return $this->failBack('Access token saknas i svar.');
        }

        // Spara/uppdatera integration
        $integration = Integration::firstOrNew([
            'site_id'  => $siteId,
        ]);
        $integration->provider = 'shopify';
        $integration->credentials = [
            'shop_domain'   => $shop,
            'access_token'  => Crypt::encryptString($accessToken),
            'scopes'        => $data['scope'] ?? null,
            'installed_at'  => now()->toIso8601String(),
        ];
        $integration->status = 'connected';
        $integration->last_error = null;
        $integration->save();

        return redirect()
            ->route('onboarding', ['step' => 3])
            ->with('success', 'Shopify anslutet – fortsätt i steg 3.');
    }

    private function failBack(string $message)
    {
        // Försök gå tillbaka till senaste kända site, annars index
        $siteId = session('shopify_oauth_site');
        session()->forget(['shopify_oauth_state', 'shopify_oauth_site']);

        if ($siteId) {
            return redirect()
                ->route('onboarding', ['step' => 3])
                ->with('error', $message);
        }

        return redirect()->route('onboarding')->with('error', $message);
    }
}
