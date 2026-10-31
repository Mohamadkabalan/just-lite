<?php

namespace App\Mail;

use App\Appointment;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

/**
 * Tells the clinic about a booking made on the site. Replying answers the client.
 */
class NewAppointment extends Mailable
{
    public function __construct(public Appointment $appointment)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            replyTo: [new Address((string) $this->appointment->email, (string) $this->appointment->name)],
            subject: 'New booking: '.$this->appointment->name.' on '.$this->appointment->appointment_date,
        );
    }

    public function content(): Content
    {
        return new Content(view: 'mail.new-appointment');
    }
}
