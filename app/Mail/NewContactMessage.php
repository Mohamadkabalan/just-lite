<?php

namespace App\Mail;

use App\Contact;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

/**
 * Tells the clinic about a contact-form message. Replying answers the sender.
 */
class NewContactMessage extends Mailable
{
    public function __construct(public Contact $contact)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            replyTo: [new Address((string) $this->contact->email, (string) $this->contact->name)],
            subject: 'New message: '.$this->contact->subject,
        );
    }

    public function content(): Content
    {
        return new Content(view: 'mail.new-contact-message');
    }
}
