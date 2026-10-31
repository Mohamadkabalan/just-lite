<?php

use App\Support\SiteLocale;

if (! function_exists('localized_route')) {
    /**
     * The URL of a named public route in the current language (/about or /en/about).
     *
     * @param  mixed  $parameters
     */
    function localized_route(string $name, $parameters = []): string
    {
        return SiteLocale::route($name, $parameters);
    }
}
