<?php

namespace Tests\Feature;

use App\Appointment;
use App\Contact;
use App\Service;
use Illuminate\Foundation\Testing\DatabaseTransactions;
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

        $this->withSession(['locale' => 'ar'])
            ->get('/service-details/'.$service->id)
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
        ])->assertOk()->assertJson(['status' => 200]);

        $this->assertDatabaseHas((new Appointment)->getTable(), [
            'email' => 'bugfix-appointment@example.test',
            'appointment_date' => '2030-12-31',
            'type_of_service' => null,
        ]);
    }
}
