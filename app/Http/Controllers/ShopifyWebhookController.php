<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class ShopifyWebhookController extends Controller
{
    private function verify(Request $request): bool
    {
        $secret = config('services.shopify.client_secret');
        $hmac   = $request->header('X-Shopify-Hmac-Sha256');
        if (!$hmac || !$secret) return false;

        $raw = $request->getContent();
        $calc = base64_encode(hash_hmac('sha256', $raw, $secret, true));

        // Tidsäkert jämförelsematch
        return hash_equals($hmac, $calc);
    }

    public function customersDataRequest(Request $request)
    {
        if (!$this->verify($request)) {
            return response('Invalid HMAC', 401);
        }
        // TODO: hantera begäran vid behov
        return response()->noContent(); // 204
    }

    public function customersRedact(Request $request)
    {
        if (!$this->verify($request)) {
            return response('Invalid HMAC', 401);
        }
        // TODO: hantera begäran vid behov
        return response()->noContent();
    }

    public function shopRedact(Request $request)
    {
        if (!$this->verify($request)) {
            return response('Invalid HMAC', 401);
        }
        // TODO: hantera begäran vid behov
        return response()->noContent();
    }
}
