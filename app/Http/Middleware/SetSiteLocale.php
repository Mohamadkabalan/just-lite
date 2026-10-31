<?php

namespace App\Http\Middleware;

use App\Support\SiteLocale;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

/**
 * Public pages take their language from the URL (/en/... or the Arabic root).
 * The choice is also kept in the session, so the admin opens in the language
 * last used on the site, as it did before languages had their own URLs.
 */
class SetSiteLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        $locale = SiteLocale::fromRequest($request);

        app()->setLocale($locale);
        Carbon::setLocale($locale);
        Session::put('locale', $locale);

        return $next($request);
    }
}
