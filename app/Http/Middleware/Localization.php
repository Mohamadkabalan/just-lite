<?php

namespace App\Http\Middleware;

use Closure;

use Carbon\Carbon;

use Illuminate\Support\Facades\Session;

class Localization

{
    /**
     * Handle an incoming request.
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */

    public function handle($request, Closure $next)
    {
        // Sessions written before /locale/{locale} was restricted may hold any string.
        if (Session::has('locale') && in_array(Session::get('locale'), config('app.available_locales'), true)) {
            $current_locale =Session::get('locale');//get saved session locale value
            app()->setLocale($current_locale); // set app localization with locale value session
            Carbon::setLocale($current_locale); //set carbon localization for date/time system with locale value session
        } else {
            $app_locale = config('app.locale'); // the app's default locale
            Carbon::setLocale($app_locale);//set carbon localization with current app localization configuration
        }
        return $next($request);
    }
}
