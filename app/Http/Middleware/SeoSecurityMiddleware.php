<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SeoSecurityMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // 1. Technical SEO: Enforce Non-WWW (301 Redirect)
        // Helps avoid "Duplicate Content" penalties in Google/Bing
        if (str_starts_with($request->getHost(), 'www.')) {
            $newUrl = str_replace('www.', '', $request->fullUrl());
            return redirect()->to($newUrl, 301);
        }

        $response = $next($request);

        // 2. Security & Header Cleanup: Remove X-Powered-By
        // Reduces server fingerprint and satisfies audit requirements
        if (method_exists($response, 'header')) {
            $response->header('X-Powered-By', '');
        }

        return $response;
    }
}
