<?php

// The messages come from the framework; this only names the form fields and
// words the few messages a visitor of the public forms can see.
return [
    'custom' => [
        'phone' => ['regex' => 'Please enter a valid phone number.'],
        'phone_number' => ['regex' => 'Please enter a valid phone number.'],
        'appointment_date' => ['after_or_equal' => 'Please choose today or a later date.'],
        'form_started' => ['required' => 'This form has expired. Please reload the page and try again.'],
        'website' => ['prohibited' => 'Please leave the website field empty.'],
    ],

    'attributes' => [
        'phone_number' => 'phone number',
        'msg_subject' => 'subject',
        'appointment_date' => 'date',
        'type_of_service' => 'type of service',
    ],
];
