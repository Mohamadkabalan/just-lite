<?php

namespace Tests\Feature;

use App\Appointment;
use App\Contact;
use App\Mail\NewAppointment;
use App\Mail\NewContactMessage;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

/**
 * The contact form and booking modal: translated replies, validation, spam
 * protection and the email to the clinic. Writes roll back.
 */
class FormTest extends TestCase
{
    use DatabaseTransactions;

    public function test_replies_are_in_the_pages_language(): void
    {
        Mail::fake();

        $this->postJson('/en/contact/create', $this->contact())->assertOk()
            ->assertJson(['status' => 200, 'message' => __('translation.message-sent', [], 'en')]);
        $this->postJson('/contact/create', $this->contact())->assertOk()
            ->assertJson(['status' => 200, 'message' => __('translation.message-sent', [], 'ar')]);
        $this->postJson('/en/appointment/create', $this->booking())->assertOk()
            ->assertJson(['message' => __('translation.appointment-sent', [], 'en')]);

        $this->postJson('/contact/create', ['phone_number' => 'call me'])->assertUnprocessable()
            ->assertJsonPath('errors.phone_number.0', 'يرجى إدخال رقم هاتف صالح.');
    }

    public function test_bookings_need_today_or_later_and_a_real_phone_number(): void
    {
        $this->postJson('/en/appointment/create', $this->booking(['appointment_date' => now()->subDay()->toDateString()]))
            ->assertUnprocessable()->assertJsonPath('errors.appointment_date.0', 'Please choose today or a later date.');
        $this->postJson('/en/appointment/create', $this->booking(['phone' => 'call me']))
            ->assertUnprocessable()->assertJsonValidationErrors(['phone']);

        Mail::fake();
        foreach (['+961 70 779 100', '٠٣/١٢٣٤٥٦', '(03) 123-456'] as $phone) {
            $this->postJson('/appointment/create', $this->booking(['phone' => $phone, 'appointment_date' => now()->toDateString()]))->assertOk();
        }
    }

    public function test_bots_are_turned_away(): void
    {
        $before = [Contact::count(), Appointment::count()];

        // The hidden "website" field is filled.
        $this->postJson('/contact/create', $this->contact(['website' => 'http://spam.example']))
            ->assertUnprocessable()->assertJsonValidationErrors(['website']);
        // Posted straight to the URL, without the form's token.
        $this->postJson('/appointment/create', $this->booking(['form_started' => null]))
            ->assertUnprocessable()->assertJsonValidationErrors(['form_started']);
        // A forged token.
        $this->postJson('/contact/create', $this->contact(['form_started' => base64_encode('0')]))
            ->assertUnprocessable()->assertJsonValidationErrors(['form_started']);
        // Sent within a second of the page loading.
        $this->postJson('/en/contact/create', $this->contact(['form_started' => form_started()]))
            ->assertUnprocessable()->assertJsonPath('errors.form_started.0', __('translation.form-too-fast', [], 'en'));

        $this->assertSame($before, [Contact::count(), Appointment::count()]);
    }

    public function test_the_clinic_is_emailed_about_bookings_and_messages(): void
    {
        Mail::fake();

        $this->postJson('/appointment/create', $this->booking(['email' => 'client@example.test', 'name' => 'Client Name']))->assertOk();
        $this->postJson('/contact/create', $this->contact(['email' => 'writer@example.test', 'msg_subject' => 'A question']))->assertOk();

        Mail::assertSent(NewAppointment::class, fn (NewAppointment $mail) => $mail->hasTo(config('site.notify_email'))
            && $mail->hasReplyTo('client@example.test')
            && str_contains($mail->render(), 'Client Name'));
        Mail::assertSent(NewContactMessage::class, fn (NewContactMessage $mail) => $mail->hasTo(config('site.notify_email'))
            && $mail->hasReplyTo('writer@example.test')
            && $mail->hasSubject('New message: A question'));
    }

    public function test_a_mail_failure_does_not_lose_the_booking(): void
    {
        // A mail server that refuses connections.
        config(['mail.default' => 'smtp', 'mail.mailers.smtp.host' => '127.0.0.1', 'mail.mailers.smtp.port' => 1]);

        $this->postJson('/appointment/create', $this->booking(['email' => 'mail-down@example.test']))
            ->assertOk()->assertJson(['status' => 200]);
        $this->assertDatabaseHas((new Appointment)->getTable(), ['email' => 'mail-down@example.test']);
    }

    public function test_forms_are_labelled_and_use_phone_keyboards(): void
    {
        $html = $this->get('/contact-us')->assertOk()->getContent();

        foreach (['name', 'email', 'phone_number', 'msg_subject', 'message', 'booking-name', 'booking-phone', 'booking-date', 'booking-message'] as $id) {
            $this->assertStringContainsString('<label for="'.$id.'"', $html, $id);
        }
        $this->assertSame(2, substr_count($html, 'type="tel"'));
        $this->assertSame(2, substr_count($html, 'name="form_started"'));
        $this->assertStringContainsString('min="'.now()->toDateString().'"', $html);
        $this->assertStringNotContainsString('jquery-ui', $html);
    }

    /**
     * @param  array<string, mixed>  $overrides
     * @return array<string, mixed>
     */
    private function contact(array $overrides = []): array
    {
        return array_merge([
            'name' => 'Form Test',
            'email' => 'form-test@example.test',
            'phone_number' => '+961 70 000 000',
            'msg_subject' => 'Form test',
            'message' => 'Automated form test.',
            'form_started' => encrypt(now()->subMinute()->timestamp),
        ], $overrides);
    }

    /**
     * @param  array<string, mixed>  $overrides
     * @return array<string, mixed>
     */
    private function booking(array $overrides = []): array
    {
        return array_merge([
            'name' => 'Form Test',
            'email' => 'form-test@example.test',
            'phone' => '+961 70 000 000',
            'appointment_date' => now()->addWeek()->toDateString(),
            'message' => 'Automated booking test.',
            'form_started' => encrypt(now()->subMinute()->timestamp),
        ], $overrides);
    }
}
