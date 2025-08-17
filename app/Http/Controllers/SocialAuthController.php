<?php

namespace App\Http\Controllers;

use App\Models\SocialIntegration;
use App\Support\CurrentCustomer;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SocialAuthController extends Controller
{
    public function facebookRedirect(Request $req)
    {
        $params = [
            'client_id'     => config('services.facebook.client_id'),
            'redirect_uri'  => config('services.facebook.redirect'),
            'response_type' => 'code',
            'scope'         => implode(',', config('services.facebook.scopes', [])),
            'state'         => csrf_token(),
        ];
        $url = 'https://www.facebook.com/v19.0/dialog/oauth?'.http_build_query($params);
        return redirect()->away($url);
    }

    public function facebookCallback(Request $req, CurrentCustomer $current)
    {
        $customer = $current->get();
        abort_unless($customer, 403);

        $code = $req->query('code');
        abort_unless($code, 400, 'Saknar code');

        $client = new Client(['timeout' => 30]);

        // 1) Byt code -> User access token
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

        // 2) Hämta sidor för användaren
        $pagesRes = $client->get('https://graph.facebook.com/v19.0/me/accounts', [
            'query' => ['access_token' => $userAccessToken, 'fields' => 'id,name,access_token'],
        ]);
        $pages = json_decode((string) $pagesRes->getBody(), true);
        $firstPage = $pages['data'][0] ?? null;
        abort_unless($firstPage, 400, 'Inga sidor hittades på kontot');

        $pageId = $firstPage['id'];
        $pageAccessToken = $firstPage['access_token'];

        // 3) Spara facebook-integration
        SocialIntegration::updateOrCreate(
            ['customer_id' => $customer->id, 'provider' => 'facebook'],
            ['page_id' => $pageId, 'access_token' => $pageAccessToken, 'status' => 'active']
        );

        // 4) Försök även få IG Business User kopplad till sidan
        try {
            $igRes = $client->get("https://graph.facebook.com/v19.0/{$pageId}", [
                'query' => ['access_token' => $pageAccessToken, 'fields' => 'instagram_business_account'],
            ]);
            $igData = json_decode((string) $igRes->getBody(), true);
            $igUserId = $igData['instagram_business_account']['id'] ?? null;

            if ($igUserId) {
                SocialIntegration::updateOrCreate(
                    ['customer_id' => $customer->id, 'provider' => 'instagram'],
                    ['ig_user_id' => $igUserId, 'access_token' => $pageAccessToken, 'status' => 'active'] // FB Page token funkar för IG Graph
                );
            }
        } catch (\Throwable $e) {
            Log::info('[IG Link] Ingen IG business eller fel', ['error' => $e->getMessage()]);
        }

        return redirect()->route('settings.social')->with('success', 'Facebook ansluten. (Och Instagram om kopplad till sidan)');
    }

    // Instagram via direkt FB-login-flöde: vi återanvänder facebookRedirect/callback.
    // Alternativ callback om du vill ha separat knapp/flow:
    public function instagramRedirect()
    {
        // Reuse Facebook scopes/flow men länka från “Anslut Instagram”
        return $this->facebookRedirect(request());
    }

    public function instagramCallback(Request $req, CurrentCustomer $current)
    {
        // Reuse FB callback – det löser IG också om sidan kopplad
        return $this->facebookCallback($req, $current);
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
            'base_uri' => 'https://api.linkedin.com/v2/',
            'timeout'  => 30,
            'headers'  => ['Authorization' => "Bearer {$accessToken}"],
        ]);

        $meRes = $api->get('me');
        $me = json_decode((string) $meRes->getBody(), true);
        $personId = $me['id'] ?? null;
        abort_unless($personId, 400, 'Kunde inte hämta profil');

        $personUrn = 'urn:li:person:' . $personId;

        // 3) Försök hitta första organisationen (om några) – annars postar vi som person
        $ownerUrn = $personUrn;
        try {
            $orgsRes = $api->get('organizationalEntityAcls', [
                'query' => [
                    'q' => 'roleAssignee',
                    'projection' => '(elements*(organizationalTarget~(id,localizedName)))',
                ],
            ]);
            $orgs = json_decode((string) $orgsRes->getBody(), true);
            $first = $orgs['elements'][0]['organizationalTarget~']['id'] ?? null;
            if ($first) {
                $ownerUrn = 'urn:li:organization:' . $first;
            }
        } catch (\Throwable $e) {
            Log::info('[LinkedIn] Inga organisationer eller fel', ['error' => $e->getMessage()]);
        }

        // 4) Spara/uppdatera social_integrations för LinkedIn
        // Vi återanvänder page_id för att lagra "owner URN" (person eller org).
        SocialIntegration::updateOrCreate(
            ['customer_id' => $customer->id, 'provider' => 'linkedin'],
            ['page_id' => $ownerUrn, 'access_token' => $accessToken, 'status' => 'active']
        );

        return redirect()->route('settings.social')->with('success', 'LinkedIn ansluten.');
    }
}
