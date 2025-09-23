<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1) Allow explicit override via header (used by /en proxy)
        $headerLocale = strtolower((string) $request->header('X-Locale'));
        if (in_array($headerLocale, ['en','sv'], true)) {
            $locale = $headerLocale;
        } else {
            // 2) Otherwise infer from first URL segment
            $segment = $request->segment(1);
            $locale = $segment === 'en' ? 'en' : 'sv';
        }

        app()->setLocale($locale);

        // Also set Carbon locale if available
        try {
            \Carbon\Carbon::setLocale($locale === 'sv' ? 'sv_SE' : 'en');
        } catch (\Throwable $e) {
            // ignore
        }

        return $next($request);
    }
}
