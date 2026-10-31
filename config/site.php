<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Public Address
    |--------------------------------------------------------------------------
    |
    | The one host search engines should index: canonical links, hreflang,
    | Open Graph URLs and the sitemap use it, whatever host a request came in
    | on (www, http, or local Docker).
    |
    */

    'url' => env('SITE_URL', 'https://just-lite.com'),

    /*
    |--------------------------------------------------------------------------
    | Business Phone Number
    |--------------------------------------------------------------------------
    |
    | International format. The call (tel:) and WhatsApp links use it; the
    | number shown on the page is "phone-info" in each language's
    | resources/lang/{ar,en}/translation.php (Arabic uses Arabic-Indic digits),
    | and tests check that both match this value.
    |
    */

    'phone' => '+96170779100',

    /*
    |--------------------------------------------------------------------------
    | Contact Email and Social Profiles
    |--------------------------------------------------------------------------
    */

    'email' => 'Layla.G.Hanna@hotmail.com',

    'social' => [
        'facebook' => 'https://www.facebook.com/bylaylahanna/',
        'instagram' => 'https://www.instagram.com/just_lite/',
        'linkedin' => 'https://www.linkedin.com/in/layla-hanna-313892204/',
    ],

    /*
    |--------------------------------------------------------------------------
    | Google Tag Manager
    |--------------------------------------------------------------------------
    |
    | Loaded only after a visitor accepts analytics cookies in the banner
    | (public/js/consent.js). Empty disables it.
    |
    */

    'gtm_id' => env('GTM_ID', 'GTM-MNGKCWV'),

    /*
    |--------------------------------------------------------------------------
    | Notifications
    |--------------------------------------------------------------------------
    |
    | New bookings and contact messages are emailed here. Sending needs the
    | MAIL_* settings in the server's .env; if mail fails, the form still
    | saves the message and the error is logged.
    |
    */

    'notify_email' => env('NOTIFY_EMAIL', 'Layla.G.Hanna@hotmail.com'),

    /*
    |--------------------------------------------------------------------------
    | Data Retention
    |--------------------------------------------------------------------------
    |
    | Contact messages and bookings older than this many months are deleted by
    | the scheduled "model:prune" task. The privacy policy states the same
    | period (resources/lang/{ar,en}/privacy.php).
    |
    */

    'retention_months' => 24,

];
