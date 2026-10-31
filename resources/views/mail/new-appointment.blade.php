@include('mail.layout', [
    'heading' => 'New booking',
    'replyName' => $appointment->name,
    'rows' => [
        'Name' => $appointment->name,
        'Email' => $appointment->email,
        'Phone' => $appointment->phone,
        'Date' => $appointment->appointment_date,
        'Package' => $appointment->type_of_service ?: '–',
        'Message' => $appointment->message,
    ],
])
