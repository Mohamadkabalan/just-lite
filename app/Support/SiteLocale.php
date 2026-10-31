<?php

namespace App\Support;

use Illuminate\Http\Request;

/**
 * The public site's language lives in the URL: Arabic (the default) at the
 * root and English under /en, so each language has its own address that
 * search engines can index. routes/web.php registers every public route twice,
 * the English copies with an "en." name prefix.
 */
class SiteLocale
{
    public const DEFAULT = 'ar';

    public const PREFIXED = 'en';

    /**
     * The language of a public URL.
     */
    public static function fromRequest(Request $request): string
    {
        return $request->segment(1) === self::PREFIXED ? self::PREFIXED : self::DEFAULT;
    }

    /**
     * The URL of a named public route in the current (or the given) language.
     *
     * @param  mixed  $parameters
     */
    public static function route(string $name, $parameters = [], ?string $locale = null): string
    {
        $locale ??= app()->getLocale();

        return route($locale === self::PREFIXED ? self::PREFIXED.'.'.$name : $name, $parameters);
    }

    /**
     * The request's path without the language prefix: "/", "/about", "/blog-details/2".
     */
    public static function basePath(Request $request): string
    {
        $segments = $request->segments();

        if (($segments[0] ?? null) === self::PREFIXED) {
            array_shift($segments);
        }

        return '/'.implode('/', $segments);
    }

    /**
     * A base path ("/about") in the given language ("/en/about").
     */
    public static function localizedPath(string $basePath, string $locale): string
    {
        if ($locale !== self::PREFIXED) {
            return $basePath;
        }

        return rtrim('/'.self::PREFIXED.$basePath, '/');
    }

    /**
     * The current page in another language, keeping the query string (?page=2).
     */
    public static function switchUrl(string $locale, ?Request $request = null): string
    {
        $request ??= request();
        $query = $request->getQueryString();

        return url(self::localizedPath(self::basePath($request), $locale)).($query ? '?'.$query : '');
    }

    /**
     * The canonical address of the current page in a language, on the public
     * host (config site.url). Paginated lists keep their page number.
     */
    public static function canonical(?string $locale = null, ?Request $request = null): string
    {
        $locale ??= app()->getLocale();
        $request ??= request();
        $page = (int) $request->query('page', '1');

        return rtrim((string) config('site.url'), '/')
            .(self::localizedPath(self::basePath($request), $locale) ?: '/')
            .($page > 1 ? '?page='.$page : '');
    }
}
