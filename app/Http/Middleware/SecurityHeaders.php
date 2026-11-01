<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    /**
     * Content-Security-Policy for the public site, in report-only mode:
     * browsers report what it would block to /api/csp-report (logged once a
     * day per violation) without blocking anything. Once the log shows no
     * reports for what the site needs, the header can become
     * "Content-Security-Policy" to enforce it. The admin (Voyager, with its
     * own inline scripts and CDNs) is not covered.
     */
    private const CSP = [
        "default-src 'self'",
        "script-src 'self' 'unsafe-inline' https://www.googletagmanager.com https://*.googletagmanager.com https://www.google-analytics.com https://*.google-analytics.com",
        "style-src 'self' 'unsafe-inline'",
        "img-src 'self' data: https://*.google-analytics.com https://*.googletagmanager.com https://*.google.com https://*.gstatic.com https://i.ytimg.com",
        "font-src 'self'",
        "connect-src 'self' https://*.google-analytics.com https://*.analytics.google.com https://*.googletagmanager.com",
        "frame-src https://www.google.com https://www.youtube-nocookie.com https://www.googletagmanager.com",
        "object-src 'none'",
        "base-uri 'self'",
        "form-action 'self'",
        "frame-ancestors 'self'",
    ];

    /**
     * Add browser security headers to every response.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->headers->set('Permissions-Policy', 'camera=(), microphone=(), geolocation=()');

        // Only over HTTPS, so a plain-HTTP setup (such as local Docker) is not pinned to HTTPS.
        if ($request->secure()) {
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000');
        }

        if (! $request->is('admin', 'admin/*') && str_contains((string) $response->headers->get('Content-Type'), 'text/html')) {
            $response->headers->set('Content-Security-Policy-Report-Only', implode('; ', self::CSP).'; report-uri '.url('/api/csp-report'));
        }

        return $response;
    }
}
