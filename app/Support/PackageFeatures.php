<?php

namespace App\Support;

/**
 * A package's features field (admin HTML) holds an optional intro text
 * followed by <li> items. The intro goes in a paragraph and the items in the
 * list, so the list contains only list items (a screen-reader requirement).
 */
class PackageFeatures
{
    /**
     * @return array{intro: string, items: string}
     */
    public static function split(?string $html): array
    {
        $html = (string) $html;
        $first = stripos($html, '<li');

        if ($first === false) {
            return ['intro' => self::text($html), 'items' => ''];
        }

        return ['intro' => self::text(substr($html, 0, $first)), 'items' => substr($html, $first)];
    }

    /**
     * The intro as plain text, blank lines kept as paragraph breaks.
     */
    private static function text(string $html): string
    {
        return trim(preg_replace("/\n{3,}/", "\n\n", str_replace("\r", '', strip_tags($html))) ?? '');
    }
}
