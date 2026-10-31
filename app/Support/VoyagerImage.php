<?php

namespace App\Support;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

/**
 * URLs for images uploaded through Voyager. Voyager stores the path with
 * backslashes, and for image fields with thumbnails configured it also writes
 * "<name>-<size>.<ext>" copies (medium, small, cropped) next to the original.
 * The images:optimize command adds a "<file>.webp" copy of each.
 */
class VoyagerImage
{
    /**
     * The URL of the image, or of its $size copy when that file exists.
     */
    public static function url(?string $path, ?string $size = null): ?string
    {
        $path = self::path($path, $size);

        return $path === null ? null : asset('storage/'.$path);
    }

    /**
     * The URL of the WebP copy of the image (or of its $size copy), if there is one.
     */
    public static function webp(?string $path, ?string $size = null): ?string
    {
        $path = self::path($path, $size);

        return $path !== null && self::disk()->exists($path.'.webp') ? asset('storage/'.$path.'.webp') : null;
    }

    /**
     * Width and height of the image (or of its $size copy), for the <img>
     * attributes, or null when the file can't be read. Cached per file.
     *
     * @return array{0: int, 1: int}|null
     */
    public static function size(?string $path, ?string $size = null): ?array
    {
        $path = self::path($path, $size);
        if ($path === null || ! self::disk()->exists($path)) {
            return null;
        }

        $file = self::disk()->path($path);

        return Cache::remember('image-size:'.md5($path.'|'.@filemtime($file)), now()->addMonth(), function () use ($file) {
            $info = @getimagesize($file);

            return $info ? [(int) $info[0], (int) $info[1]] : null;
        });
    }

    /**
     * The storage path of the image or of its $size copy (when it exists).
     */
    private static function path(?string $path, ?string $size): ?string
    {
        if ($path === null || $path === '') {
            return null;
        }

        $path = str_replace('\\', '/', $path);

        if ($size !== null) {
            $variant = (string) preg_replace('/(\.[^.\/]+)$/', '-'.$size.'$1', $path);

            if ($variant !== $path && self::disk()->exists($variant)) {
                return $variant;
            }
        }

        return $path;
    }

    private static function disk(): \Illuminate\Contracts\Filesystem\Filesystem
    {
        return Storage::disk(config('voyager.storage.disk', 'public'));
    }
}
