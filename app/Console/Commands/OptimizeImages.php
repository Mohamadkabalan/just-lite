<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

/**
 * Writes a WebP copy next to every uploaded JPEG/PNG ("photo.png" ->
 * "photo.png.webp"), at most 1600px wide. Pages serve the copy to browsers
 * that support WebP (see App\Support\VoyagerImage::webp) and the original to
 * the rest. Originals are never changed. Copies that would not be smaller are
 * skipped. Runs after each deploy and daily, so new uploads are picked up.
 */
class OptimizeImages extends Command
{
    protected $signature = 'images:optimize
        {--quality=80 : WebP quality, 1-100}
        {--max-width=1600 : Wider images are scaled down in the copy}
        {--force : Rebuild copies that already exist}';

    protected $description = 'Create smaller WebP copies of uploaded images';

    public function handle(): int
    {
        if (! function_exists('imagewebp') || ! (gd_info()['WebP Support'] ?? false)) {
            $this->warn('GD without WebP support: nothing to do.');

            return self::SUCCESS;
        }

        $disk = Storage::disk(config('voyager.storage.disk', 'public'));
        $quality = max(1, min(100, (int) $this->option('quality')));
        $maxWidth = max(100, (int) $this->option('max-width'));
        $made = $skipped = $failed = 0;
        $saved = 0;

        foreach ($disk->allFiles() as $file) {
            if (! preg_match('/\.(jpe?g|png)$/i', $file)) {
                continue;
            }

            $target = $file.'.webp';
            if ($disk->exists($target) && ! $this->option('force')) {
                continue;
            }

            $source = $disk->path($file);
            $result = $this->convert($source, $disk->path($target), $quality, $maxWidth);

            if ($result === null) {
                $failed++;
            } elseif ($result === 0) {
                $skipped++;
            } else {
                $made++;
                $saved += $result;
            }
        }

        $this->info(sprintf('WebP copies: %d made (%.1f MB smaller), %d skipped (not smaller), %d unreadable.', $made, $saved / 1048576, $skipped, $failed));

        return self::SUCCESS;
    }

    /**
     * @return int|null bytes saved, 0 when the copy would not be smaller, null when the file can't be read
     */
    private function convert(string $source, string $target, int $quality, int $maxWidth): ?int
    {
        $info = @getimagesize($source);
        if ($info === false) {
            return null;
        }

        $image = match ($info[2]) {
            IMAGETYPE_JPEG => @imagecreatefromjpeg($source),
            IMAGETYPE_PNG => @imagecreatefrompng($source),
            default => false,
        };
        if ($image === false) {
            return null;
        }

        if (imagesx($image) > $maxWidth) {
            $scaled = imagescale($image, $maxWidth);
            imagedestroy($image);
            if ($scaled === false) {
                return null;
            }
            $image = $scaled;
        }

        imagepalettetotruecolor($image);
        imagealphablending($image, true);
        imagesavealpha($image, true);

        $temp = $target.'.tmp';
        $ok = imagewebp($image, $temp, $quality);
        imagedestroy($image);

        $original = (int) filesize($source);
        if (! $ok || ! is_file($temp) || filesize($temp) >= $original) {
            @unlink($temp);

            return 0;
        }

        $saved = $original - (int) filesize($temp);
        rename($temp, $target);

        return $saved;
    }
}
