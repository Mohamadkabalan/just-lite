<?php

namespace App\Support;

use Illuminate\Support\Facades\Storage;

/**
 * URLs for images uploaded through Voyager. Voyager stores the path with
 * backslashes, and for image fields with thumbnails configured it also writes
 * "<name>-<size>.<ext>" copies (medium, small, cropped) next to the original.
 */
class VoyagerImage
{
    /**
     * The URL of the image, or of its $size copy when that file exists.
     */
    public static function url(?string $path, ?string $size = null): ?string
    {
        if ($path === null || $path === '') {
            return null;
        }

        $path = str_replace('\\', '/', $path);

        if ($size !== null) {
            $variant = (string) preg_replace('/(\.[^.\/]+)$/', '-'.$size.'$1', $path);

            if ($variant !== $path && Storage::disk(config('voyager.storage.disk', 'public'))->exists($variant)) {
                $path = $variant;
            }
        }

        return asset('storage/'.$path);
    }
}
