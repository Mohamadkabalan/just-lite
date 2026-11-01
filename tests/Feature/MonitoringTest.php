<?php

namespace Tests\Feature;

use App\Mail\ErrorReport;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use RuntimeException;
use Tests\TestCase;

/**
 * Daily logs and an email alert when the site hits an error.
 */
class MonitoringTest extends TestCase
{
    use DatabaseTransactions;

    public function test_an_error_is_emailed_once_an_hour(): void
    {
        Mail::fake();
        // A fresh cache: the once-an-hour marker must not leak between runs.
        config(['cache.default' => 'array']);
        config(['app.debug' => false, 'site.error_email' => 'developer@example.test']);
        Route::get('/__test/broken', fn () => throw new RuntimeException('Database is on fire'));

        $this->get('/__test/broken')->assertStatus(500);
        $this->get('/__test/broken')->assertStatus(500);
        $this->get('/this-page-does-not-exist')->assertNotFound();

        Mail::assertSent(ErrorReport::class, 1);
        Mail::assertSent(ErrorReport::class, fn (ErrorReport $mail) => $mail->hasTo('developer@example.test')
            && str_contains($mail->rows['Error'], 'Database is on fire')
            && str_contains($mail->rows['Request'], 'GET ')
            && str_contains($mail->rows['Where'], 'MonitoringTest.php'));
    }

    public function test_no_alert_without_an_address(): void
    {
        Mail::fake();
        // A fresh cache: the once-an-hour marker must not leak between runs.
        config(['cache.default' => 'array']);
        config(['app.debug' => false, 'site.error_email' => null]);
        Route::get('/__test/broken', fn () => throw new RuntimeException('boom'));

        $this->get('/__test/broken')->assertStatus(500);

        Mail::assertNothingSent();
    }

    public function test_logs_rotate_daily(): void
    {
        $this->assertSame(['daily'], config('logging.channels.stack.channels'));
        $this->assertSame(14, (int) config('logging.channels.daily.days'));
    }
}
