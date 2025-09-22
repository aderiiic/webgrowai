<?php

namespace App\Http\Controllers;

use App\Models\SocialIntegration;
use App\Support\CurrentCustomer;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SocialAuthController extends Controller
{
    public function facebookRedirect(Request $req)
    {
        $scopes = config('services.facebook.scopes', []);

        $state = bin2hex(random_bytes(16));
        session(['facebook_state' => $state]);

        $params = [
            'client_id'     => config('services.facebook.client_id'),
            'redirect_uri'  => config('services.facebook.redirect'),
            'response_type' => 'code',
            'scope'         => implode(',', $scopes),
            'state'         => $state,
            'auth_type'     => 'rerequest', // fråga igen om något nekats tidigare
        ];

        $url = 'https://www.facebook.com/v19.0/dialog/oauth?' . http_build_query($params);
        return redirect()->away($url);
    }

    public function facebookCallback(Request $req, CurrentCustomer $current)
    {
        $savedState = session('facebook_state');
        abort_unless($req->query('state') === $savedState, 400, 'Ogiltig state');

        $customer = $current->get();
        Log::info('[FB] Callback', ['customer' => $customer?->id]);
        abort_unless($customer, 403);

        $siteId = $current->getSiteId();
        abort_unless($siteId, 400, 'Ingen aktiv sajt vald vid koppling.');

        $code = $req->query('code');
        abort_unless($code, 400, 'Saknar code');

        Log::info('[FB] Auth code mottagen');

        $client = new Client(['timeout' => 30]);

        try {
            // 1) Byt code -> user access token
            $tokenRes = $client->get('https://graph.facebook.com/v19.0/oauth/access_token', [
                'query' => [
                    'client_id'     => config('services.facebook.client_id'),
                    'client_secret' => config('services.facebook.client_secret'),
                    'redirect_uri'  => config('services.facebook.redirect'),
                    'code'          => $code,
                ],
            ]);
            $token = json_decode((string) $tokenRes->getBody(), true);
            $userAccessToken = $token['access_token'] ?? null;
            abort_unless($userAccessToken, 400, 'Kunde inte hämta user access token');

            // 2) Försök long‑lived
            try {
                $llRes = $client->get('https://graph.facebook.com/v19.0/oauth/access_token', [
                    'query' => [
                        'grant_type'        => 'fb_exchange_token',
                        'client_id'         => config('services.facebook.client_id'),
                        'client_secret'     => config('services.facebook.client_secret'),
                        'fb_exchange_token' => $userAccessToken,
                    ],
                ]);
                $llBody = json_decode((string) $llRes->getBody(), true);
                if (!empty($llBody['access_token'])) {
                    $userAccessToken = $llBody['access_token'];
                }
            } catch (\Throwable $e) {
                Log::info('[FB] Long‑lived token misslyckades', ['error' => $e->getMessage()]);
            }

            // 3) Hämta sidor och välj en (MVP: första – kan utökas till UI-val)
            $pagesRes = $client->get('https://graph.facebook.com/v19.0/me/accounts', [
                'query' => [
                    'access_token' => $userAccessToken,
                    'fields'       => 'id,name,access_token',
                    'limit'        => 50,
                ],
            ]);
            $pages = json_decode((string) $pagesRes->getBody(), true);
            $firstPage = $pages['data'][0] ?? null;

            if (!$firstPage) {
                return redirect()
                    ->route('settings.social')
                    ->with('error', 'Inga Facebook-sidor hittades för kontot.');
            }

            $pageId          = $firstPage['id'] ?? null;
            $pageAccessToken = $firstPage['access_token'] ?? null;
            abort_unless($pageId && $pageAccessToken, 400, 'Sidan saknar giltigt Page Access Token');

            // 4) Spara Facebook-integration per sajt
            SocialIntegration::updateOrCreate(
                ['customer_id' => $customer->id, 'site_id' => $siteId, 'provider' => 'facebook'],
                ['page_id' => $pageId, 'access_token' => $pageAccessToken, 'status' => 'active']
            );
            Log::info('[FB] Page sparad', ['site_id' => $siteId, 'page_id' => $pageId]);

            // 5) Auto‑koppla IG Business (robust: testa flera edges)
            $ig = $this->ensureInstagramFromFacebook($siteId, $pageId, $pageAccessToken, $customer->id);
            if ($ig) {
                Log::info('[FB] IG auto‑kopplad', [
                    'site_id'   => $siteId,
                    'ig_user_id'=> $ig['id'] ?? null,
                    'username'  => $ig['username'] ?? null
                ]);
            } else {
                Log::info('[FB] IG ej hittad/kopplad för sidan', ['site_id' => $siteId, 'page_id' => $pageId]);
            }

            return redirect()->route('settings.social')->with('success', 'Facebook ansluten för denna sajt. (Instagram om kopplad)');
        } catch (ClientException $e) {
            $resp = $e->getResponse();
            $body = $resp ? (string) $resp->getBody() : null;
            Log::error('[FB] ClientException', [
                'status' => $resp?->getStatusCode(),
                'body'   => $body,
                'msg'    => $e->getMessage(),
            ]);
            return redirect()->route('settings.social')->with('error', 'Facebook-inloggningen misslyckades: ' . ($body ?: $e->getMessage()));
        } catch (\Throwable $e) {
            Log::error('[FB] Okänt fel', ['error' => $e->getMessage()]);
            return redirect()->route('settings.social')->with('error', 'Facebook-inloggningen misslyckades: ' . $e->getMessage());
        }
    }

    public function instagramRedirect()
    {
        // Återanvänd FB‑flödet (samma scopes), men utlösts från “Anslut Instagram”
        return $this->facebookRedirect(request());
    }

    public function instagramCallback(Request $req, CurrentCustomer $current)
    {
        // Återanvänd FB‑callback – den sparar både FB och IG per sajt om möjligt
        return $this->facebookCallback($req, $current);
    }

    public function linkedinRedirect(Request $req)
    {
        Log::info('[LinkedIn] Redirecting');
        $scopes = config('services.linkedin.scopes', []);
        $params = [
            'response_type' => 'code',
            'client_id'     => config('services.linkedin.client_id'),
            'redirect_uri'  => config('services.linkedin.redirect_uri'),
            'state'         => csrf_token(),
            'scope'         => implode(' ', $scopes),
        ];
        $url = 'https://www.linkedin.com/oauth/v2/authorization?' . http_build_query($params);
        return redirect()->away($url);
    }

    public function linkedinCallback(Request $req, CurrentCustomer $current)
    {
        $customer = $current->get();
        abort_unless($customer, 403);

        $siteId = $current->getSiteId();
        abort_unless($siteId, 400, 'Ingen aktiv sajt vald vid koppling.');

        $code = $req->query('code');
        abort_unless($code, 400, 'Saknar code');

        $client = new Client(['timeout' => 30]);
        Log::info('[LinkedIn] Callback');

        // 1) code -> access token
        $tokenRes = $client->post('https://www.linkedin.com/oauth/v2/accessToken', [
            'form_params' => [
                'grant_type'    => 'authorization_code',
                'code'          => $code,
                'redirect_uri'  => config('services.linkedin.redirect_uri'),
                'client_id'     => config('services.linkedin.client_id'),
                'client_secret' => config('services.linkedin.client_secret'),
            ],
        ]);
        Log::info('[LinkedIn] Access token mottagen');
        $token = json_decode((string) $tokenRes->getBody(), true);
        $accessToken = $token['access_token'] ?? null;
        abort_unless($accessToken, 400, 'Kunde inte hämta access token');

        // 2) userinfo (person)
        $api = new Client([
            'base_uri' => 'https://api.linkedin.com/',
            'timeout'  => 30,
            'headers'  => [
                'Authorization' => "Bearer {$accessToken}",
                'X-Restli-Protocol-Version' => '2.0.0',
            ],
        ]);

        $userinfoRes = $api->get('v2/userinfo');
        $userinfo = json_decode((string) $userinfoRes->getBody(), true);
        $personSub = $userinfo['sub'] ?? null;
        abort_unless($personSub, 400, 'Kunde inte hämta userinfo');

        $ownerUrn = 'urn:li:person:' . $personSub;

        // 3) Spara/uppdatera
        SocialIntegration::updateOrCreate(
            ['customer_id' => $customer->id, 'site_id' => $siteId, 'provider' => 'linkedin'],
            ['page_id' => $ownerUrn, 'access_token' => $accessToken, 'status' => 'active']
        );

        return redirect()->route('settings.social')->with('success', 'LinkedIn ansluten.');
    }

    // ------------------------------------------------------------------------------------
    // Hjälpare för IG‑auto‑koppling via Page
    // ------------------------------------------------------------------------------------

    private function http(): Client
    {
        return new Client(['base_uri' => 'https://graph.facebook.com/v19.0/', 'timeout' => 20]);
    }

    // Försök hitta IG via flera edges och spara per site
    private function ensureInstagramFromFacebook(int $siteId, string $pageId, string $token, ?int $customerId = null): ?array
    {
        $tryEdges = [
            ['path' => $pageId,                     'fields' => 'instagram_business_account{id,username}',  'key' => 'instagram_business_account'],
            ['path' => $pageId,                     'fields' => 'connected_instagram_account{id,username}', 'key' => 'connected_instagram_account'],
            ['path' => $pageId . '/instagram_accounts', 'fields' => null,                                  'key' => 'data'],
        ];

        $http = $this->http();
        $ig   = null;

        foreach ($tryEdges as $index => $try) {
            try {
                $query = ['access_token' => $token];
                if ($try['fields']) {
                    $query['fields'] = $try['fields'];
                } else {
                    $query['fields'] = 'id,username';
                    $query['limit']  = 1;
                }

                Log::info('[FB] Försöker IG edge', [
                    'edge_index' => $index,
                    'path' => $try['path'],
                    'query' => $query
                ]);

                $res  = $http->get($try['path'], ['query' => $query]);
                $data = json_decode((string) $res->getBody(), true);

                Log::info('[FB] IG response', [
                    'edge_index' => $index,
                    'response_data' => $data
                ]);

                if ($try['key'] === 'data') {
                    $candidate = $data['data'][0] ?? null;
                } else {
                    $candidate = $data[$try['key']] ?? null;
                }

                if (!empty($candidate['id'])) {
                    $ig = $candidate;
                    Log::info('[FB] IG hittad via edge', [
                        'edge_index' => $index,
                        'ig_data' => $ig
                    ]);
                    break;
                }
            } catch (\Throwable $e) {
                Log::warning('[FB] IG edge misslyckades', [
                    'edge_index' => $index,
                    'path' => $try['path'],
                    'error' => $e->getMessage(),
                    'response_body' => $e instanceof ClientException ? (string) $e->getResponse()?->getBody() : null
                ]);
            }
        }

        if (!$ig || empty($ig['id'])) {
            Log::warning('[FB] Ingen IG hittad för sidan', [
                'site_id' => $siteId,
                'page_id' => $pageId
            ]);
            return null;
        }

        SocialIntegration::updateOrCreate(
            ['customer_id' => $customerId, 'site_id' => $siteId, 'provider' => 'instagram'],
            ['ig_user_id' => (string) $ig['id'], 'access_token' => $token, 'status' => 'active']
        );

        return $ig;
    }
}
