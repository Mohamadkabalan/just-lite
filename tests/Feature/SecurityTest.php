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

    public function test_the_user_role_cannot_manage_users_but_keeps_its_inbox(): void
    {
        $admin = $this->admin();
        $user = $this->userWithRole(DB::table('roles')->where('name', 'user')->value('id'), 'security-user@example.test');

        $this->actingAs($user)->get('/admin/users')->assertForbidden();
        $this->actingAs($user)->get("/admin/users/{$admin->id}/edit")->assertForbidden();

        foreach (['/admin/contacts', '/admin/appointments', '/admin/blogs'] as $url) {
            $this->actingAs($user)->get($url)->assertOk();
        }
    }

    public function test_user_permissions_alone_cannot_change_other_users(): void
    {
        // A role that can manage users but not roles, as the "user" role could before.
        $roleId = DB::table('roles')->insertGetId(['name' => 'security-editor', 'display_name' => 'Security editor']);
        $keys = ['browse_admin', 'browse_users', 'read_users', 'edit_users', 'add_users', 'delete_users'];
        foreach (DB::table('permissions')->whereIn('key', $keys)->pluck('id') as $permissionId) {
            DB::table('permission_role')->insert(['permission_id' => $permissionId, 'role_id' => $roleId]);
        }

        $admin = $this->admin();
        $adminRoleId = $admin->role_id;
        $editor = $this->userWithRole($roleId, 'security-editor@example.test');

        $this->actingAs($editor)->get("/admin/users/{$admin->id}/edit")->assertForbidden();
        $this->actingAs($editor)->put("/admin/users/{$admin->id}", [
            'name' => 'Taken over',
            'email' => 'taken-over@example.test',
            'password' => 'new-password-123',
        ])->assertForbidden();
        $this->actingAs($editor)->put("/admin/users/{$editor->id}", [
            'name' => $editor->name,
            'email' => $editor->email,
            'role_id' => $adminRoleId,
        ]);
        $this->actingAs($editor)->post('/admin/users', [
            'name' => 'New admin',
            'email' => 'new-admin@example.test',
            'password' => 'new-password-123',
            'role_id' => $adminRoleId,
        ])->assertForbidden();
        $this->actingAs($editor)->delete("/admin/users/{$admin->id}")->assertForbidden();

        $this->assertSame('Security Test Admin', $admin->fresh()->name);
        $this->assertSame($roleId, (int) $editor->fresh()->role_id, 'Users must not change their own role.');
        $this->assertDatabaseMissing('users', ['email' => 'new-admin@example.test']);

        // Admins still manage users.
        $this->actingAs($admin)->get("/admin/users/{$editor->id}/edit")->assertOk();
    }

    public function test_admin_passwords_need_twelve_characters(): void
    {
        $admin = $this->admin();
        $roleId = DB::table('roles')->where('name', 'user')->value('id');

        $this->actingAs($admin)->post('/admin/users', [
            'name' => 'Short password',
            'email' => 'short-password@example.test',
            'password' => 'abc123',
            'role_id' => $roleId,
        ])->assertSessionHasErrors('password');
        $this->assertDatabaseMissing('users', ['email' => 'short-password@example.test']);

        $this->actingAs($admin)->put("/admin/users/{$admin->id}", [
            'name' => $admin->name,
            'email' => $admin->email,
            'password' => 'short',
        ])->assertSessionHasErrors('password');
    }

    private function admin(): User
    {
        return $this->userWithRole(DB::table('roles')->where('name', 'admin')->value('id'), 'security-admin@example.test', 'Security Test Admin');
    }

    private function userWithRole(int $roleId, string $email, string $name = 'Security Test User'): User
    {
        $user = new User;
        $user->forceFill([
            'name' => $name,
            'email' => $email,
            'password' => bcrypt(Str::random(40)),
            'role_id' => $roleId,
        ])->save();

        return $user;
    }

    private function png(): string
    {
        $image = imagecreatetruecolor(4, 4);
        ob_start();
        imagepng($image);

        return (string) ob_get_clean();
    }
}
