<?php

use App\Support\SiteLocale;

if (! function_exists('form_started')) {
    /**
     * The encrypted "form shown at" time the public forms send back, checked by
     * App\Rules\FilledByAPerson.
     */
    function form_started(): string
    {
        return encrypt(now()->timestamp);
    }
}

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
