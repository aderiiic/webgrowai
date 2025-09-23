<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LocaleProxyController extends Controller
{
    public function handle(Request $request, ?string $any = null): Response
    {
        // Remove the leading 'en' segment and forward internally
        $path = $any ?? '';
        $path = '/'.ltrim($path ?? '', '/');

        $newRequest = Request::create(
            $path === '//' ? '/' : $path,
            $request->method(),
            $request->all(),
            $request->cookies->all(),
            $request->allFiles(),
            $request->server->all()
        );

        // Preserve headers (like Accept, Authorization if present)
        foreach ($request->headers->all() as $key => $values) {
            foreach ($values as $value) {
                $newRequest->headers->set($key, $value, false);
            }
        }

        // Explicitly mark locale as English for the forwarded request so middleware keeps it
        $newRequest->headers->set('X-Locale', 'en');

        return app()->handle($newRequest);
    }
}
