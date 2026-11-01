<?php

namespace App\Support;

use App\Mail\ErrorReport;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

/**
 * Emails config site.error_email when the site reports an error, so problems
 * are noticed without reading the log. The same error (class, file and line)
 * is sent at most once an hour. Called from the exception handler, which
 * only reports real errors (not 404s or validation failures).
 */
class ErrorAlert
{
    public static function send(Throwable $e): void
    {
        $to = config('site.error_email');

        if (! $to) {
            return;
        }

        $key = 'error-alert:'.sha1(get_class($e).'|'.$e->getFile().'|'.$e->getLine());

        try {
            if (! Cache::add($key, true, now()->addHour())) {
                return;
            }

            Mail::to($to)->send(new ErrorReport($e, request()));
        } catch (Throwable $mailError) {
            // Never report this one: a broken mailer would loop.
            Log::warning('Could not send the error alert: '.$mailError->getMessage());
        }
    }
}
