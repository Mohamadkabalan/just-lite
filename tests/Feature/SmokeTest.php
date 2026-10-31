<?php

namespace Tests\Feature;

use App\Appointment;
use App\Blog;
use App\Contact;
use App\Models\User;
use App\Service;
use App\WeeklyDietPlan;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Tests\TestCase;

/**
 * Smoke tests for the public site and the Voyager admin, used as the safety
 * net for the Laravel upgrade.
 *
 * They run against the staging copy of the production database (.env.testing).
 * The content tables were created through Voyager, so there are no migrations
 * to build them from scratch. Every write is rolled back after each test.
 */
class SmokeTest extends TestCase
{
    use DatabaseTransactions;

    private const LOCALES = ['ar', 'en'];

    public function test_public_pages_render_in_every_locale(): void
    {
        $failures = [];

        foreach (self::LOCALES as $locale) {
            foreach ($this->publicUrls() as $url) {
                $status = $this->withSession(['locale' => $locale])->get($url)->status();

                if ($status !== 200) {
                    $failures[] = "[{$locale}] {$url} -> {$status}";
                }
            }
        }

        $this->assertSame([], $failures, "Pages not returning 200:\n".implode("\n", $failures));
    }

    public function test_ajax_pagination_endpoints_render(): void
    {
        foreach (['/services/fetch_data?page=1', '/blogs/fetch_data?page=1'] as $url) {
            $this->get($url, ['X-Requested-With' => 'XMLHttpRequest'])->assertOk();
        }
    }

    public function test_locale_switch_stores_locale_in_session(): void
    {
        $this->from('/about')
            ->get('/locale/en')
            ->assertRedirect('/about')
            ->assertSessionHas('locale', 'en');
    }

    public function test_contact_form_saves_the_message(): void
    {
        $this->postJson('/contact/create', [
            'name' => 'Smoke Test',
            'email' => 'smoke-contact@example.test',
            'phone_number' => '0000000000',
            'msg_subject' => 'Smoke test subject',
            'message' => 'Automated smoke test message.',
        ])->assertOk()->assertJson(['status' => 200]);

        $this->assertDatabaseHas((new Contact)->getTable(), [
            'email' => 'smoke-contact@example.test',
            'subject' => 'Smoke test subject',
        ]);
    }

    public function test_appointment_form_saves_the_appointment(): void
    {
        $this->postJson('/appointment/create', [
            'name' => 'Smoke Test',
            'email' => 'smoke-appointment@example.test',
            'phone' => '0000000000',
            'appointment_date' => now()->addWeek()->format('Y-m-d'),
            'type_of_service' => 'Smoke test',
            'message' => 'Automated smoke test appointment.',
        ])->assertOk()->assertJson(['status' => 200]);

        $this->assertDatabaseHas((new Appointment)->getTable(), [
            'email' => 'smoke-appointment@example.test',
        ]);
    }

    public function test_admin_login_page_renders(): void
    {
        $this->get('/admin/login')->assertOk();
    }

    public function test_admin_screens_render_for_an_admin(): void
    {
        $roleId = DB::table('roles')->where('name', 'admin')->value('id');
        $this->assertNotNull($roleId, 'No "admin" role found in the roles table.');

        $admin = new User;
        $admin->forceFill([
            'name' => 'Smoke Test Admin',
            'email' => 'smoke-admin@example.test',
            'password' => bcrypt(Str::random(40)),
            'role_id' => $roleId,
        ])->save();

        $urls = ['/admin', '/admin/settings', '/admin/media', '/admin/profile', '/admin/database', '/admin/bread'];

        foreach (DB::table('menus')->orderBy('id')->pluck('id') as $menuId) {
            $urls[] = "/admin/menus/{$menuId}/builder";
        }

        foreach (DB::table('data_types')->orderBy('id')->pluck('name') as $table) {
            $urls[] = "/admin/bread/{$table}/edit";
        }

        foreach (DB::table('data_types')->orderBy('id')->get(['slug', 'model_name']) as $type) {
            $urls[] = "/admin/{$type->slug}";
            $urls[] = "/admin/{$type->slug}/create";

            if (class_exists($type->model_name)) {
                $model = new $type->model_name;
                $key = $model->newQuery()->orderBy($model->getKeyName())->value($model->getKeyName());

                if ($key !== null) {
                    $urls[] = "/admin/{$type->slug}/{$key}/edit";
                }
            }
        }

        $failures = [];

        foreach ($urls as $url) {
            $status = $this->actingAs($admin)->get($url)->status();

            if ($status !== 200) {
                $failures[] = "{$url} -> {$status}";
            }
        }

        $this->assertSame([], $failures, "Admin screens not returning 200:\n".implode("\n", $failures));
    }

    /**
     * @return array<int, string>
     */
    private function publicUrls(): array
    {
        $urls = ['/', '/about', '/testimonials', '/services', '/blogs', '/contact-us'];

        $details = [
            Blog::class => '/blog-details/',
            Service::class => '/service-details/',
            WeeklyDietPlan::class => '/diet-plan/',
        ];

        foreach ($details as $model => $prefix) {
            $id = $model::query()->orderBy('id')->value('id');

            if ($id !== null) {
                $urls[] = $prefix.$id;
            }
        }

        return $urls;
    }
}
