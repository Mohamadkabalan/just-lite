<?php

// Error pages (resources/views/errors). "4xx"/"5xx" cover any other status.
return [
    '401' => ['title' => 'Please log in', 'message' => 'You need to log in to see this page.'],
    '403' => ['title' => 'Access denied', 'message' => 'You don\'t have permission to view this page.'],
    '404' => ['title' => 'Page not found', 'message' => 'The page you are looking for doesn\'t exist or has moved.'],
    '419' => ['title' => 'Page expired', 'message' => 'This page was open for too long. Please go back, reload it and try again.'],
    '429' => ['title' => 'Too many requests', 'message' => 'Please wait a minute and try again.'],
    '500' => ['title' => 'Something went wrong', 'message' => 'Sorry, something went wrong on our side. Please try again in a few minutes.'],
    '503' => ['title' => 'Back soon', 'message' => 'We are updating the site and will be back in a few minutes.'],
    '4xx' => ['title' => 'Something is not right', 'message' => 'This request could not be completed. Please go back and try again.'],
    '5xx' => ['title' => 'Something went wrong', 'message' => 'Sorry, something went wrong on our side. Please try again in a few minutes.'],

    'home' => 'Back to the home page',
    'whatsapp' => 'Message us on WhatsApp',
];
