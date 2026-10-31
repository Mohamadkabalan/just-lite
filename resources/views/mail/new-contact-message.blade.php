@include('mail.layout', [
    'heading' => 'New message',
    'replyName' => $contact->name,
    'rows' => [
        'Name' => $contact->name,
        'Email' => $contact->email,
        'Phone' => $contact->phone,
        'Subject' => $contact->subject,
        'Message' => $contact->message,
    ],
])
