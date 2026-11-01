<?php

namespace App\Mail;

use Illuminate\Http\Request;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Support\Str;
use Throwable;

/**
 * A short error report for the developer (App\Support\ErrorAlert). It holds
 * no request data beyond the address and method; the stack trace is in the log.
 */
class ErrorReport extends Mailable
{
    /** @var array<string, string> */
    public array $rows;

    public function __construct(Throwable $e, ?Request $request)
    {
        $this->rows = [
            'Error' => class_basename($e).': '.Str::limit($e->getMessage(), 500),
            'Where' => str_replace(base_path().DIRECTORY_SEPARATOR, '', $e->getFile()).':'.$e->getLine(),
            'Request' => $request && (! app()->runningInConsole() || app()->runningUnitTests()) ? $request->method().' '.$request->url() : 'console / scheduled task',
            'When' => now()->toDateTimeString().' '.config('app.timezone'),
            'Site' => config('app.env').' at '.config('site.url'),
        ];
    }

    public function envelope(): Envelope
    {
        return new Envelope(subject: '[Just Lite] '.Str::limit($this->rows['Error'], 120));
    }

    public function content(): Content
    {
        return new Content(view: 'mail.error-report');
    }
}
