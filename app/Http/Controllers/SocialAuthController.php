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
}
