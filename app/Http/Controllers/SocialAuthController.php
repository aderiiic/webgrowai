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

        $statePayload = [
            'nonce'   => bin2hex(random_bytes(16)),
            'site'    => (string) $req->query('site', ''),
            'channel' => (string) $req->query('channel', 'facebook'),
        ];

        $state = base64_encode(json_encode($statePayload));
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

        $stateJson = json_decode(base64_decode($savedState) ?: '{}', true);
        $stateSite = $stateJson['site'] ?? null;
        $channel   = $stateJson['channel'] ?? 'facebook';

        $customer = $current->get();
        Log::info('[FB] Callback', ['customer' => $customer?->id]);
        abort_unless($customer, 403);

        $siteId = $stateSite ?: $current->getSiteId();
        abort_unless($siteId, 400, 'Ingen aktiv sajt vald vid koppling.');

        $code = $req->query('code');
        abort_unless($code, 400, 'Saknar code');

        session()->forget('facebook_state');

        Log::info('[FB] Auth code mottagen');

        $client = new Client(['timeout' => 30]);

        try {
            // 1) code -> user token
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

            // 2) Long‑lived
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

            // 3) Hämta sidor användaren gett tillgång till
            $pagesRes = $client->get('https://graph.facebook.com/v19.0/me/accounts', [
                'query' => [
                    'access_token' => $userAccessToken,
                    'fields'       => 'id,name,access_token',
                    'limit'        => 200,
                ],
            ]);
            $pages = json_decode((string) $pagesRes->getBody(), true);
            $pageList  = array_values($pages['data'] ?? []);

            if (empty($pageList)) {
                return redirect()->route('settings.social')->with('error', 'Inga Facebook-sidor hittades.');
            }

            // Om bara en sida -> spara direkt och försök IG
            if (count($pageList) === 1) {
                $pageId          = $pageList[0]['id'] ?? null;
                $pageAccessToken = $pageList[0]['access_token'] ?? null;
                abort_unless($pageId && $pageAccessToken, 400, 'Sidan saknar giltigt Page Access Token');

                SocialIntegration::updateOrCreate(
                    ['customer_id' => $customer->id, 'site_id' => $siteId, 'provider' => 'facebook'],
                    ['page_id' => $pageId, 'access_token' => $pageAccessToken, 'status' => 'active']
                );

                // Försök IG‑auto
                $igCandidates = $this->collectIgAccountsForPages($pageList, $client);
                if (count($igCandidates) === 1) {
                    $ig = $igCandidates[0];
                    SocialIntegration::updateOrCreate(
                        ['customer_id' => $customer->id, 'site_id' => $siteId, 'provider' => 'instagram'],
                        ['ig_user_id' => (string)$ig['id'], 'access_token' => $ig['token'], 'status' => 'active']
                    );
                } elseif (count($igCandidates) > 1) {
                    session(['ig_connect_pending' => [
                        'site_id' => $siteId,
                        'accounts' => $igCandidates,
                    ]]);
                }

                return redirect()->route('settings.social')->with('success', 'Facebook ansluten. ' . (count($igCandidates) > 1 ? 'Välj Instagram‑konto nedan.' : ''));
            }

            // Flera sidor – bygg pending listor för FB och IG
            $fbPendingPages = array_map(fn($p) => [
                'id'    => $p['id'],
                'name'  => $p['name'] ?? $p['id'],
                'token' => $p['access_token'] ?? null,
            ], $pageList);

            $igCandidates = $this->collectIgAccountsForPages($pageList, $client);

            session([
                'fb_connect_pending' => [
                    'site_id' => $siteId,
                    'channel' => $channel,
                    'pages'   => $fbPendingPages,
                ],
                'ig_connect_pending' => [
                    'site_id' => $siteId,
                    'accounts' => $igCandidates, // kan vara tom, 1 eller flera
                ],
            ]);

            return redirect()->route('settings.social')->with('success', 'Välj Facebook‑sida' . (count($igCandidates) ? ' och Instagram‑konto' : '') . ' för denna sajt.');

        } catch (ClientException $e) {
            $status = $e->getResponse()?->getStatusCode();
            $msg = $status === 400 ? 'Begäran nekades av Meta. Kontrollera val och behörigheter.' :
                ($status === 401 ? 'Ogiltig inloggning. Försök igen.' :
                    ($status === 403 ? 'Saknar behörighet.' : 'Tekniskt fel hos Meta.'));
            return redirect()->route('settings.social')->with('error', $msg);
        } catch (\Throwable $e) {
            Log::error('[FB] Okänt fel', ['error' => $e->getMessage()]);
            return redirect()->route('settings.social')->with('error', 'Facebook-inloggningen misslyckades: ' . $e->getMessage());
        }
    }

    private function collectIgAccountsForPages(array $pages, Client $http): array
    {
        $found = [];
        foreach ($pages as $p) {
            $pageId = $p['id'] ?? null;
            $token  = $p['access_token'] ?? null;
            if (!$pageId || !$token) {
                continue;
            }

            $edges = [
                ['path' => $pageId, 'fields' => 'instagram_business_account{id,username}',  'key' => 'instagram_business_account'],
                ['path' => $pageId, 'fields' => 'connected_instagram_account{id,username}', 'key' => 'connected_instagram_account'],
                ['path' => $pageId.'/instagram_accounts', 'fields' => null, 'key' => 'data'],
            ];

            foreach ($edges as $try) {
                try {
                    $query = ['access_token' => $token];
                    if ($try['fields']) {
                        $query['fields'] = $try['fields'];
                    } else {
                        $query['fields'] = 'id,username';
                        $query['limit']  = 5;
                    }
                    $res  = $http->get($try['path'], ['query' => $query]);
                    $data = json_decode((string)$res->getBody(), true);

                    if ($try['key'] === 'data') {
                        foreach (($data['data'] ?? []) as $acc) {
                            if (!empty($acc['id'])) {
                                $found[] = ['id' => (string)$acc['id'], 'username' => $acc['username'] ?? $acc['id'], 'token' => $token];
                            }
                        }
                    } else {
                        $acc = $data[$try['key']] ?? null;
                        if (!empty($acc['id'])) {
                            $found[] = ['id' => (string)$acc['id'], 'username' => $acc['username'] ?? $acc['id'], 'token' => $token];
                        }
                    }
                } catch (\Throwable) {
                    // prova nästa
                }
            }
        }

        // Unika på id
        $unique = [];
        foreach ($found as $row) {
            $unique[$row['id']] = $row;
        }
        return array_values($unique);
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
        Log::info('[LinkedIn] Redirecting to', ['url' => $url]);
        return redirect()->away($url);
    }

    public function linkedinCallback(Request $req, CurrentCustomer $current)
    {
        Log::info('[LinkedIn] Callback');
        $customer = $current->get();
        abort_unless($customer, 403);

        $siteId = $current->getSiteId();
        abort_unless($siteId, 400, 'Ingen aktiv sajt vald vid koppling.');

        $code = $req->query('code');
        abort_unless($code, 400, 'Saknar code');

        $client = new Client(['timeout' => 30]);

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

        $userinfoRes = $api->get('v2/me');
        $userinfo = json_decode((string) $userinfoRes->getBody(), true);

        $personId = $userinfo['id'] ?? null;
        abort_unless($personId, 400, 'Kunde inte hämta användarprofil');

        $ownerUrn = 'urn:li:person:' . $personId;

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

    public function facebookChoose(Request $req, CurrentCustomer $current)
    {
        $req->validate([
            'site_id' => 'required|integer',
            'page_id' => 'required|string',
        ]);

        $pending = session('fb_connect_pending');
        abort_unless($pending && (int)$pending['site_id'] === (int)$req->input('site_id'), 400, 'Ogiltig begäran');

        $selected = collect($pending['pages'] ?? [])->firstWhere('id', $req->input('page_id'));
        abort_unless($selected && !empty($selected['token']), 400, 'Ogiltigt sidval');

        $customer = $current->get();
        abort_unless($customer, 403);

        SocialIntegration::updateOrCreate(
            ['customer_id' => $customer->id, 'site_id' => (int)$pending['site_id'], 'provider' => 'facebook'],
            ['page_id' => $selected['id'], 'access_token' => $selected['token'], 'status' => 'active']
        );

        session()->forget('fb_connect_pending');

        return redirect()->route('settings.social')->with('success', 'Facebook-sida kopplad till vald sajt.');
    }

    public function instagramChoose(Request $req, CurrentCustomer $current)
    {
        $req->validate([
            'site_id'   => 'required|integer',
            'ig_user_id'=> 'required|string',
        ]);

        $pending = session('ig_connect_pending');
        abort_unless($pending && (int)$pending['site_id'] === (int)$req->input('site_id'), 400, 'Ogiltig begäran');

        $selected = collect($pending['accounts'] ?? [])->firstWhere('id', $req->input('ig_user_id'));
        abort_unless($selected && !empty($selected['token']), 400, 'Ogiltigt konto');

        $customer = $current->get();
        abort_unless($customer, 403);

        SocialIntegration::updateOrCreate(
            ['customer_id' => $customer->id, 'site_id' => (int)$pending['site_id'], 'provider' => 'instagram'],
            ['ig_user_id' => (string)$selected['id'], 'access_token' => $selected['token'], 'status' => 'active']
        );

        session()->forget('ig_connect_pending');

        return redirect()->route('settings.social')->with('success', 'Instagram-konto kopplat till vald sajt.');
    }
}
