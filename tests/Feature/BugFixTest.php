<?php

namespace Tests\Feature;

use App\About;
use App\Appointment;
use App\Blog;
use App\Contact;
use App\Models\User;
use App\Service;
use App\Support\VoyagerImage;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Tests\TestCase;

/**
 * Regression tests for the bugs fixed in the post-upgrade bug scan.
 * Like SmokeTest, they run against the staging/Docker copy of the database
 * and every write is rolled back.
 */
class BugFixTest extends TestCase
{
    use DatabaseTransactions;

    public function test_unsupported_locale_is_rejected(): void
    {
        $this->get('/locale/fr')->assertNotFound()->assertSessionMissing('locale');
        $this->get('/locale/x y')->assertNotFound()->assertSessionMissing('locale');
    }

    public function test_an_invalid_locale_already_in_the_session_does_not_break_pages(): void
    {
        $this->withSession(['locale' => 'x y'])->get('/about')->assertOk();
    }

    public function test_the_site_shows_one_phone_number(): void
    {
        $digits = ltrim(config('site.phone'), '+');
        $ascii = fn (string $s) => strtr($s, ['٠' => '0', '١' => '1', '٢' => '2', '٣' => '3', '٤' => '4', '٥' => '5', '٦' => '6', '٧' => '7', '٨' => '8', '٩' => '9']);

        foreach (['ar', 'en'] as $locale) {
            $this->assertSame($digits, preg_replace('/\D/', '', $ascii(trans('translation.phone-info', [], $locale))), "phone-info ({$locale})");

            $html = $this->get($locale === 'en' ? '/en/contact-us' : '/contact-us')->assertOk()->getContent();
            preg_match_all('/961\d{8}/', $ascii($html), $found);

            $this->assertSame([$digits], array_values(array_unique($found[0])), "numbers on /contact-us ({$locale})");
            $this->assertStringContainsString('tel:'.config('site.phone'), $html);
            $this->assertStringContainsString('api.whatsapp.com/send?phone='.$digits, $html);
        }
    }

    public function test_each_language_loads_its_own_bootstrap_build(): void
    {
        $blog = Blog::query()->orderBy('id')->firstOrFail();

        foreach (['/', '/blog-details/'.$blog->id] as $url) {
            $this->get($url)->assertOk()
                ->assertSee('css/bootstrap.rtl.min.css', false)
                ->assertDontSee('css/bootstrap.min.css', false)
                ->assertDontSee('cdn.jsdelivr.net/npm/bootstrap', false);

            $this->get('/en'.rtrim($url, '/'))->assertOk()
                ->assertSee('css/bootstrap.min.css', false)
                ->assertDontSee('css/bootstrap.rtl.min.css', false)
                ->assertDontSee('cdn.jsdelivr.net/npm/bootstrap', false);
        }
    }

    public function test_blog_text_keeps_its_line_breaks_and_stays_escaped(): void
    {
        $blog = new Blog;
        $blog->title_en = 'Line break test';
        $blog->text_en = "Ingredients:\n2 eggs <b>fresh</b>\n1 cup flour";
        $blog->save();

        $this->get('/en/blog-details/'.$blog->id)->assertOk()
            ->assertSee("Ingredients:<br />\n2 eggs &lt;b&gt;fresh&lt;/b&gt;<br />\n1 cup flour", false);
    }

    public function test_about_background_shows_the_image_or_nothing(): void
    {
        $about = About::query()->firstOrFail();

        // Without an image the theme's placeholder (images/about/about-bg.png) must not show.
        $about->image = null;
        $about->save();
        $this->get('/about')->assertOk()->assertSee('background-image: none;', false)->assertDontSee('url(/storage/)', false);

        $about->image = 'abouts\\May2023\\photo.jpg';
        $about->save();
        $this->get('/about')->assertOk()->assertSee('url('.asset('storage/abouts/May2023/photo.jpg').')', false);
    }

    public function test_empty_sections_are_hidden_and_empty_lists_say_so(): void
    {
        \App\NutritionSpecialFeature::query()->delete();
        $this->get('/en/about')->assertOk()->assertDontSee('Nutrition Special Feature');

        // Testimonials without a name get no empty heading.
        \App\Testimonial::query()->update(['title_en' => null]);
        $this->assertDoesNotMatchRegularExpression('#<h3>\s*</h3>#', $this->get('/en/testimonials')->getContent());

        Blog::query()->delete();
        $this->get('/en/blogs')->assertOk()->assertSee(__('translation.no-posts', [], 'en'));
        $this->get('/blogs')->assertOk()->assertSee(__('translation.no-posts', [], 'ar'));
    }

    public function test_voyager_image_uses_a_size_copy_only_when_it_exists(): void
    {
        Storage::fake(config('voyager.storage.disk'));
        Storage::disk(config('voyager.storage.disk'))->put('banners/x.png', 'png');
        Storage::disk(config('voyager.storage.disk'))->put('banners/x-medium.png', 'png');

        $this->assertSame(asset('storage/banners/x-medium.png'), VoyagerImage::url('banners\\x.png', 'medium'));
        $this->assertSame(asset('storage/banners/x.png'), VoyagerImage::url('banners\\x.png', 'small'));
        $this->assertSame(asset('storage/banners/x.png'), VoyagerImage::url('banners\\x.png'));
        $this->assertNull(VoyagerImage::url(null, 'medium'));
    }

    public function test_admin_forms_refuse_empty_content(): void
    {
        $admin = new User;
        $admin->forceFill([
            'name' => 'Bug Fix Admin',
            'email' => 'bugfix-admin@example.test',
            'password' => bcrypt(Str::random(40)),
            'role_id' => DB::table('roles')->where('name', 'admin')->value('id'),
        ])->save();

        $before = Blog::count();
        $this->actingAs($admin)->post('/admin/blogs', ['title_en' => '', 'title_ar' => ''])
            ->assertSessionHasErrors(['title_en', 'title_ar', 'text_en', 'text_ar']);
        $this->assertSame($before, Blog::count());

        // Existing records still save without re-uploading their image.
        $blog = Blog::query()->orderBy('id')->firstOrFail();
        $this->actingAs($admin)->put("/admin/blogs/{$blog->id}", [
            'title_en' => $blog->title_en.' (edited)',
            'title_ar' => $blog->title_ar,
            'text_en' => $blog->text_en,
            'text_ar' => $blog->text_ar,
        ])->assertSessionHasNoErrors();
        $this->assertSame($blog->title_en.' (edited)', $blog->fresh()->title_en);
    }

    public function test_the_template_demo_shop_is_gone(): void
    {
        $this->get('/shop')->assertNotFound();
    }

    public function test_detail_pages_return_404_for_unknown_ids(): void
    {
        foreach (['/blog-details/', '/service-details/', '/diet-plan/'] as $prefix) {
            $this->get($prefix.'999999999')->assertNotFound();
        }
    }

    public function test_pagination_endpoints_need_an_ajax_request(): void
    {
        $this->get('/services/fetch_data')->assertNotFound();
        $this->get('/blogs/fetch_data')->assertNotFound();
    }

    public function test_service_details_show_the_arabic_title(): void
    {
        $service = Service::query()->whereNotNull('title_Ar')->where('title_Ar', '!=', '')->firstOrFail();

        $this->get('/service-details/'.$service->id)
            ->assertOk()
            ->assertSee($service->title_Ar);
    }

    public function test_unauthenticated_browser_requests_redirect_to_the_admin_login(): void
    {
        $this->get('/api/user')->assertRedirect(route('voyager.login'));
        $this->getJson('/api/user')->assertUnauthorized();
    }

    public function test_contact_form_rejects_incomplete_messages(): void
    {
        $before = Contact::count();

        $this->postJson('/contact/create', ['name' => '', 'email' => 'not-an-email'])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['name', 'email', 'phone_number', 'msg_subject', 'message']);

        $this->assertSame($before, Contact::count());
    }

    public function test_appointment_form_rejects_incomplete_bookings(): void
    {
        $before = Appointment::count();

        $this->postJson('/appointment/create', ['email' => 'not-an-email', 'appointment_date' => 'soon'])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['name', 'email', 'phone', 'appointment_date', 'message']);

        $this->assertSame($before, Appointment::count());
    }

    public function test_appointment_form_accepts_a_booking_without_a_package(): void
    {
        $this->postJson('/appointment/create', [
            'name' => 'Bug Fix Test',
            'email' => 'bugfix-appointment@example.test',
            'phone' => '0000000000',
            'appointment_date' => '12/31/2030',
            'message' => 'Booked from the modal without choosing a package.',
            'form_started' => encrypt(now()->subMinute()->timestamp),
        ])->assertOk()->assertJson(['status' => 200]);

        $this->assertDatabaseHas((new Appointment)->getTable(), [
            'email' => 'bugfix-appointment@example.test',
            'appointment_date' => '2030-12-31',
            'type_of_service' => null,
        ]);
    }
}
