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
            // Om användaren tidigare nekat någon permission: be Facebook att fråga igen
            'auth_type'     => 'rerequest',
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

        // Viktigt: läs aktiv sajt och säkerställ att vi sparar per SAJT
        $siteId = $current->getSiteId();
        abort_unless($siteId, 400, 'Ingen aktiv sajt vald vid koppling.');

        $code = $req->query('code');
        abort_unless($code, 400, 'Saknar code');

        Log::info('[FB] Auth code mottagen');

        $client = new Client(['timeout' => 30]);

        try {
            // 1) code -> user access token
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

            // 2) försök long-lived
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
                Log::info('[FB] Long-lived token misslyckades', ['error' => $e->getMessage()]);
            }

            // 3) Hämta sidor + välj en (du kan utöka UI för att välja)
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

            $pageId = $firstPage['id'] ?? null;
            $pageAccessToken = $firstPage['access_token'] ?? null;
            abort_unless($pageId && $pageAccessToken, 400, 'Sidan saknar giltigt Page Access Token');

            // 4) Spara Facebook per SAJT
            SocialIntegration::updateOrCreate(
                ['customer_id' => $customer->id, 'site_id' => $siteId, 'provider' => 'facebook'],
                ['page_id' => $pageId, 'access_token' => $pageAccessToken, 'status' => 'active']
            );

            // 5) Försök även IG Business User för samma sida per SAJT
            try {
                $igRes = $client->get("https://graph.facebook.com/v19.0/{$pageId}", [
                    'query' => ['access_token' => $pageAccessToken, 'fields' => 'instagram_business_account'],
                ]);
                $igData = json_decode((string) $igRes->getBody(), true);
                $igUserId = $igData['instagram_business_account']['id'] ?? null;

                if ($igUserId) {
                    SocialIntegration::updateOrCreate(
                        ['customer_id' => $customer->id, 'site_id' => $siteId, 'provider' => 'instagram'],
                        ['ig_user_id' => $igUserId, 'access_token' => $pageAccessToken, 'status' => 'active']
                    );
                }
            } catch (\Throwable $e) {
                Log::info('[IG Link] Ingen IG business eller fel', ['error' => $e->getMessage()]);
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

    public function instagramCallback(Request $req, CurrentCustomer $current)
    {
        // Återanvänd FB-flödet – nu sparas det per sajt i facebookCallback
        return $this->facebookCallback($req, $current);
    }


    // Instagram via direkt FB-login-flöde: vi återanvänder facebookRedirect/callback.
    // Alternativ callback om du vill ha separat knapp/flow:
    public function instagramRedirect()
    {
        // Reuse Facebook scopes/flow men länka från “Anslut Instagram”
        return $this->facebookRedirect(request());
    }

    public function linkedinRedirect(Request $req)
    {
        Log::info('[LinkedIn] Redirecting');
        $scopes = config('services.linkedin.scopes', []);
        Log::info('[LinkedIn] Scopes', ['scopes' => $scopes]);
        $params = [
            'response_type' => 'code',
            'client_id'     => config('services.linkedin.client_id'),
            'redirect_uri'  => config('services.linkedin.redirect_uri'),
            'state'         => csrf_token(),
            'scope'         => implode(' ', $scopes),
        ];
        Log::info('[LinkedIn] Params', ['params' => $params]);
        $url = 'https://www.linkedin.com/oauth/v2/authorization?' . http_build_query($params);
        Log::info('[LinkedIn] URL', ['url' => $url]);
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

        // 1) Byt code -> Access token
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

        // 2) Hämta person-id
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

        $personUrn = 'urn:li:person:' . $personSub;

        // 3) Försök hitta första organisationen (om några) – annars postar vi som person
        $ownerUrn = $personUrn;
//        try {
//            $orgsRes = $api->get('organizationalEntityAcls', [
//                'query' => [
//                    'q' => 'roleAssignee',
//                    'projection' => '(elements*(organizationalTarget~(id,localizedName)))',
//                ],
//            ]);
//            $orgs = json_decode((string) $orgsRes->getBody(), true);
//            $first = $orgs['elements'][0]['organizationalTarget~']['id'] ?? null;
//            if ($first) {
//                $ownerUrn = 'urn:li:organization:' . $first;
//            }
//        } catch (\Throwable $e) {
//            Log::info('[LinkedIn] Inga organisationer eller fel', ['error' => $e->getMessage()]);
//        }

        // 4) Spara/uppdatera social_integrations för LinkedIn
        // Vi återanvänder page_id för att lagra "owner URN" (person eller org).
        SocialIntegration::updateOrCreate(
            ['customer_id' => $customer->id, 'site_id' => $siteId, 'provider' => 'linkedin'],
            ['page_id' => $ownerUrn, 'access_token' => $accessToken, 'status' => 'active']
        );

        return redirect()->route('settings.social')->with('success', 'LinkedIn ansluten.');
    }
}
