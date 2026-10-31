<?php

namespace Tests\Feature;

use App\Appointment;
use App\Contact;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

/**
 * Consent before analytics, no third-party requests by default, the privacy
 * policy and the retention clean-up. Writes roll back.
 */
class PrivacyTest extends TestCase
{
    use DatabaseTransactions;

    public function test_analytics_load_only_after_consent(): void
    {
        $this->get('/')->assertOk()
            ->assertDontSee('googletagmanager.com/gtm.js', false)
            ->assertSee('id="consent-banner"', false);

        $this->withUnencryptedCookie('site_consent', 'granted')->get('/')->assertOk()
            ->assertSee('googletagmanager.com/gtm.js', false)
            ->assertSee(config('site.gtm_id'), false);

        $this->withUnencryptedCookie('site_consent', 'denied')->get('/en')->assertOk()
            ->assertDontSee('googletagmanager.com/gtm.js', false)
            ->assertDontSee('googletagmanager.com/ns.html', false);
    }

    public function test_the_banner_is_hidden_once_the_visitor_has_chosen(): void
    {
        $banner = fn (string $html) => preg_match('/<div id="consent-banner"[^>]*>/', $html, $m) ? $m[0] : '';

        $this->assertStringNotContainsString(' hidden', $banner($this->get('/about')->getContent()));
        $this->assertStringContainsString(' hidden', $banner($this->withUnencryptedCookie('site_consent', 'denied')->get('/about')->getContent()));
    }

    public function test_pages_make_no_requests_to_google_by_default(): void
    {
        $this->assertStringNotContainsString('fonts.googleapis.com', (string) file_get_contents(public_path('css/style.css')));

        foreach (['/', '/en/contact-us', '/about'] as $url) {
            $this->get($url)->assertOk()
                ->assertDontSee('fonts.googleapis.com', false)
                ->assertDontSee('<iframe src="https://www.google.com/maps', false);
        }

        // The map loads only when asked; the video uses YouTube's no-cookie player.
        $this->get('/contact-us')->assertSee('data-map-src="https://www.google.com/maps/embed', false)->assertSee('data-map-load', false);
        $this->assertStringContainsString('youtube-nocookie.com', (string) file_get_contents(public_path('js/custom.js')));
    }

    public function test_the_privacy_policy_is_linked_and_states_the_retention_period(): void
    {
        $this->get('/en/privacy-policy')->assertOk()
            ->assertSee('<title>Privacy policy | Just Lite</title>', false)
            ->assertSee(config('site.retention_months').' months')
            ->assertSee(config('site.email'));
        $this->get('/privacy-policy')->assertOk()->assertSee(__('privacy.title', [], 'ar'))->assertSee('٢٤');

        // Footer and both forms link to it.
        $html = $this->get('/en/contact-us')->getContent();
        $this->assertSame(4, substr_count($html, 'href="'.route('en.privacy').'"'), 'footer, banner and two forms');
        $this->assertStringContainsString('data-consent-reset', $html);
    }

    public function test_old_messages_and_bookings_are_cleaned_up(): void
    {
        $old = $this->contact(now()->subMonths(25));
        $recent = $this->contact(now()->subMonths(23));

        $prunable = (new Contact)->prunable()->pluck('id');
        $this->assertTrue($prunable->contains($old->id));
        $this->assertFalse($prunable->contains($recent->id));
        $this->assertStringContainsString('created_at', (new Appointment)->prunable()->toSql());

        $commands = collect(app(Schedule::class)->events())->pluck('command')->implode("\n");
        $this->assertStringContainsString('model:prune', $commands);
        $this->assertStringContainsString('db:backup', $commands);
    }

    private function contact(\DateTimeInterface $createdAt): Contact
    {
        $contact = new Contact;
        $contact->name = 'Retention test';
        $contact->email = 'retention@example.test';
        $contact->subject = 'Retention';
        $contact->message = 'Retention test';
        $contact->created_at = $createdAt;
        $contact->save();

        return $contact;
    }
}
