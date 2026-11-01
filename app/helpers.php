<?php

use App\Support\SiteLocale;

if (! function_exists('asset_versioned')) {
    /**
     * asset() with the file's modification time as ?v=..., so browsers can cache
     * CSS and JS for a year (public/.htaccess) and still get a changed file at once.
     */
    function asset_versioned(string $path): string
    {
        $file = public_path($path);

        return asset($path).(is_file($file) ? '?v='.filemtime($file) : '');
    }
}

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
