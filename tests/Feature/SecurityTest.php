<?php

namespace Tests\Feature;

use App\Blog;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use TCG\Voyager\Support\UploadGuard;
use Tests\TestCase;

/**
 * Regression tests for the security audit. Like SmokeTest, they run against
 * the staging/Docker copy of the database and every write is rolled back.
 */
class SecurityTest extends TestCase
{
    use DatabaseTransactions;

    public function test_responses_carry_security_headers(): void
    {
        foreach (['/', '/admin/login'] as $url) {
            $this->get($url)
                ->assertHeader('X-Content-Type-Options', 'nosniff')
                ->assertHeader('X-Frame-Options', 'SAMEORIGIN')
                ->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin')
                ->assertHeader('Permissions-Policy');
        }
    }

    public function test_public_forms_are_rate_limited(): void
    {
        // One budget per IP, shared by both forms.
        for ($i = 0; $i < 5; $i++) {
            $this->postJson('/contact/create', [])->assertUnprocessable();
            $this->postJson('/appointment/create', [])->assertUnprocessable();
        }

        $this->postJson('/contact/create', [])->assertTooManyRequests();
        $this->postJson('/appointment/create', [])->assertTooManyRequests();
    }

    public function test_blog_title_is_escaped(): void
    {
        $blog = new Blog;
        $blog->title_en = '<script>alert(1)</script>';
        $blog->save();

        $this->withSession(['locale' => 'en'])
            ->get('/blog-details/'.$blog->id)
            ->assertOk()
            ->assertDontSee('<script>alert(1)</script>', false)
            ->assertSee('&lt;script&gt;alert(1)&lt;/script&gt;', false);
    }

    public function test_media_manager_rejects_scripts_disguised_as_images(): void
    {
        Storage::fake(config('voyager.storage.disk'));
        $admin = $this->admin();
        $png = $this->png();

        foreach (['shell.php', 'shell.php.png', 'shell.phtml', '.htaccess'] as $name) {
            $this->actingAs($admin)
                ->postJson('/admin/media/upload', [
                    'file' => UploadedFile::fake()->createWithContent($name, $png.'<?php system($_GET["c"]);'),
                    'upload_path' => '/',
                ])
                ->assertOk()
                ->assertJson(['success' => false]);
        }

        $this->assertSame([], Storage::disk(config('voyager.storage.disk'))->allFiles());
    }

    public function test_media_manager_still_accepts_images(): void
    {
        Storage::fake(config('voyager.storage.disk'));

        $this->actingAs($this->admin())
            ->postJson('/admin/media/upload', [
                'file' => UploadedFile::fake()->createWithContent('photo.final.PNG', $this->png()),
                'upload_path' => '/',
            ])
            ->assertOk()
            ->assertJson(['success' => true]);

        Storage::disk(config('voyager.storage.disk'))->assertExists('photo.final.PNG');
    }

    public function test_compass_is_disabled_in_every_environment(): void
    {
        $admin = $this->admin();
        $this->app['env'] = 'local';
        config(['voyager.compass_in_production' => true]);

        $this->actingAs($admin)->get('/admin/compass')->assertNotFound();
        // Outside "testing" CSRF is enforced again, so send a matching token.
        $this->actingAs($admin)->withSession(['_token' => 'test-token'])
            ->post('/admin/compass', ['_token' => 'test-token', 'command' => 'about'])
            ->assertNotFound();
        $this->actingAs($admin)->get('/admin')->assertOk()->assertDontSee(route('voyager.compass.index'), false);
    }

    public function test_upload_guard_recognises_script_file_names(): void
    {
        foreach (['x.php', 'x.PHP', 'x.php7', 'x.php.jpg', 'x.phtml', 'x.phar', '.htaccess', '.user.ini', 'a/b/x.php'] as $name) {
            $this->assertTrue(UploadGuard::isScriptFileName($name), $name);
        }

        foreach (['photo.jpg', 'php.jpg', 'my.photo.png', 'video.mp4', 'philosophy.png'] as $name) {
            $this->assertFalse(UploadGuard::isScriptFileName($name), $name);
        }
    }

    private function admin(): User
    {
        $admin = new User;
        $admin->forceFill([
            'name' => 'Security Test Admin',
            'email' => 'security-admin@example.test',
            'password' => bcrypt(Str::random(40)),
            'role_id' => DB::table('roles')->where('name', 'admin')->value('id'),
        ])->save();

        return $admin;
    }

    private function png(): string
    {
        $image = imagecreatetruecolor(4, 4);
        ob_start();
        imagepng($image);

        return (string) ob_get_clean();
    }
}
