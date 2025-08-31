<?php

namespace App\Http\Controllers\Analytics;

use App\Http\Controllers\Controller;
use App\Models\Ga4Integration;
use App\Support\CurrentCustomer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use League\OAuth2\Client\Provider\Google as GoogleProvider;
use Carbon\Carbon;

class Ga4OAuthController extends Controller
{
    private function provider(): GoogleProvider
    {
        return new GoogleProvider([
            'clientId'     => (string) config('services.google.client_id'),
            'clientSecret' => (string) config('services.google.client_secret'),
            'redirectUri'  => route('analytics.ga4.callback'),
        ]);
    }

    public function connect(CurrentCustomer $current)
    {
        $customer = $current->get();
        abort_unless($customer, 403);

        $siteId = (int) ($current->getSiteId() ?: 0);
        abort_unless($siteId > 0 && $customer->sites()->whereKey($siteId)->exists(), 404, 'Välj en sajt i topbaren.');

        $provider = $this->provider();

        $authUrl = $provider->getAuthorizationUrl([
            'scope'       => ['https://www.googleapis.com/auth/analytics.readonly'],
            'access_type' => 'offline',
            'prompt'      => 'consent',
        ]);

        session()->put('oauth2state', $provider->getState());
        session()->put('ga4_site', $siteId);

        return redirect()->away($authUrl);
    }

    public function callback(Request $request)
    {
        $state = (string) $request->query('state', '');
        $expected = (string) session('oauth2state', '');
        abort_if($state === '' || $state !== $expected, 400, 'Ogiltigt state.');
        session()->forget('oauth2state');

        $siteId = (int) session('ga4_site');
        abort_unless($siteId > 0, 400);

        $code = (string) $request->query('code', '');
        abort_unless($code !== '', 400);

        $provider = $this->provider();

        try {
            $token = $provider->getAccessToken('authorization_code', ['code' => $code]);
        } catch (\Throwable $e) {
            abort(400, 'OAuth misslyckades.');
        }

        $access  = (string) $token->getToken();
        $refresh = (string) ($token->getRefreshToken() ?? '');
        $expRaw  = $token->getExpires(); // Kan vara UNIX epoch (rekommenderat), ibland TTL

        // Robust beräkning: epoch vs TTL
        $expiresAt = null;
        if (is_numeric($expRaw)) {
            $exp = (int) $expRaw;
            $nowTs = now()->getTimestamp();
            // Om värdet ser ut som en framtida UNIX-tidsstämpel (> nu + 5 min) => epoch
            if ($exp > $nowTs + 300) {
                $expiresAt = Carbon::createFromTimestamp($exp);
            } else {
                // Annars tolka som TTL-sekunder
                $expiresAt = now()->addSeconds(max(300, $exp));
            }
        } else {
            $expiresAt = now()->addHour();
        }

        Ga4Integration::updateOrCreate(
            ['site_id' => $siteId],
            [
                'provider'       => 'oauth',
                'access_token'   => $access ? encrypt($access) : null,
                'refresh_token'  => $refresh ? encrypt($refresh) : DB::raw('refresh_token'),
                'expires_at'     => $expiresAt,
                'status'         => 'connected',
            ]
        );

        return redirect()
            ->route('analytics.settings')
            ->with('success', 'Google kopplat. Välj GA4‑property.');
    }

    public function select(Request $request)
    {
        $data = $request->validate([
            'site_id'     => ['nullable', 'integer'],
            'property_id' => ['required', 'string', 'max:120'],
            'stream_id'   => ['nullable', 'string', 'max:120'],
            'hostname'    => ['nullable', 'string', 'max:190'],
        ]);

        $siteId = (int) ($data['site_id'] ?? session('ga4_site'));
        abort_unless($siteId > 0, 400);

        Ga4Integration::where('site_id', $siteId)->update([
            'property_id' => (string) $data['property_id'],
            'stream_id'   => !empty($data['stream_id']) ? (string) $data['stream_id'] : null,
            'hostname'    => !empty($data['hostname']) ? Str::lower((string) $data['hostname']) : null,
        ]);

        return redirect()
            ->route('analytics.settings')
            ->with('success', 'GA4‑property vald.');
    }
}
