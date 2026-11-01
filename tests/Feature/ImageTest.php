<?php

namespace Tests\Feature;

use App\Blog;
use App\Support\VoyagerImage;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/**
 * WebP copies of uploads (images:optimize), <picture> markup and image sizes.
 */
class ImageTest extends TestCase
{
    use DatabaseTransactions;

    public function test_the_optimizer_writes_smaller_webp_copies_and_leaves_originals(): void
    {
        $disk = Storage::fake(config('voyager.storage.disk'));
        $disk->put('blogs/photo.png', $this->png(1700, 400));
        $original = $disk->get('blogs/photo.png');

        $this->artisan('images:optimize')->assertSuccessful();

        $disk->assertExists('blogs/photo.png.webp');
        $this->assertSame($original, $disk->get('blogs/photo.png'));
        $this->assertLessThan(strlen($original), $disk->size('blogs/photo.png.webp'));
        $this->assertSame(1600, getimagesize($disk->path('blogs/photo.png.webp'))[0], 'scaled to 1600px');

        // A second run leaves existing copies alone.
        $before = $disk->lastModified('blogs/photo.png.webp');
        $this->artisan('images:optimize')->assertSuccessful();
        $this->assertSame($before, $disk->lastModified('blogs/photo.png.webp'));
    }

    public function test_voyager_image_finds_the_webp_copy_and_the_size(): void
    {
        $disk = Storage::fake(config('voyager.storage.disk'));
        $disk->put('blogs/a.png', $this->png(300, 200));
        $disk->put('blogs/a-medium.png', $this->png(150, 100));
        $disk->put('blogs/a-medium.png.webp', 'webp');

        $this->assertSame(asset('storage/blogs/a-medium.png.webp'), VoyagerImage::webp('blogs\\a.png', 'medium'));
        $this->assertNull(VoyagerImage::webp('blogs\\a.png'));
        $this->assertSame([150, 100], VoyagerImage::size('blogs\\a.png', 'medium'));
        $this->assertSame([300, 200], VoyagerImage::size('blogs\\a.png'));
        $this->assertNull(VoyagerImage::size('blogs\\missing.png'));
    }

    public function test_pages_offer_the_webp_copy_with_the_image_size(): void
    {
        $disk = Storage::fake(config('voyager.storage.disk'));
        $disk->put('blogs/test.png', $this->png(400, 300));
        $disk->put('blogs/test.png.webp', 'webp');

        $blog = new Blog;
        $blog->title_en = 'Picture test';
        $blog->title_ar = 'صورة';
        $blog->text_en = 'Text';
        $blog->text_ar = 'نص';
        $blog->image = 'blogs\\test.png';
        $blog->save();

        $this->get('/en/blog-details/'.$blog->id)->assertOk()
            ->assertSee('<source type="image/webp" srcset="'.asset('storage/blogs/test.png.webp').'">', false)
            ->assertSee('<img src="'.asset('storage/blogs/test.png').'" alt="Picture test" width="400" height="300"', false);
    }

    private function png(int $width, int $height): string
    {
        $image = imagecreatetruecolor($width, $height);
        // Per-pixel noise, like a photo: PNG compresses it poorly, WebP well.
        mt_srand(42);
        for ($x = 0; $x < $width; $x++) {
            for ($y = 0; $y < $height; $y++) {
                imagesetpixel($image, $x, $y, ($x * 7 + $y * 3) % 256 << 16 | mt_rand(0, 255) << 8 | ($x + $y) % 256);
            }
        }
        ob_start();
        imagepng($image);

        return (string) ob_get_clean();
    }
}
