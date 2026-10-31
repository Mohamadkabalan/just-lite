<?php

namespace App\Support;

use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Mail;
use Throwable;

/**
 * Emails the clinic (config site.notify_email) about new bookings and
 * messages. Sending happens after the response, so visitors never wait for
 * the mail server, and a mail failure is logged without affecting the form:
 * the booking or message is already saved.
 */
class Notify
{
    public static function afterResponse(Mailable $mail): void
    {
        $to = config('site.notify_email');

        if (! $to) {
            return;
        }

        app()->terminating(function () use ($mail, $to) {
            try {
                Mail::to($to)->send($mail);
            } catch (Throwable $e) {
                report($e);
            }
        });
    }
}
