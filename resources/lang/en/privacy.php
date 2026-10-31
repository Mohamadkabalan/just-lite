<?php

// The privacy policy page (/privacy-policy). :email, :phone and :months are
// filled in from config/site.php. Review this text whenever the site starts
// collecting something new.
return [
    'title' => 'Privacy policy',
    'description' => 'How the Just Lite website uses your personal data: what we collect, why, for how long, and your rights.',
    'updated' => 'Last updated: 31 October 2026',
    'intro' => 'This policy explains what personal data the Just Lite website collects, why, and what you can do about it.',
    'sections' => [
        [
            'heading' => 'Who we are',
            'paragraphs' => [
                'Just Lite is the nutrition clinic of registered dietitian Layla Hanna, Mar Elias Street, El Mina, Tripoli, Lebanon. For any question about your data, write to :email or call :phone.',
            ],
        ],
        [
            'heading' => 'What we collect and why',
            'items' => [
                'Contact form: your name, email, phone number, subject and message, so we can answer you.',
                'Booking form: your name, email, phone number, preferred date, the package you chose and your message, so we can arrange and confirm your appointment.',
                'Analytics, only if you accept cookies: Google Analytics records which pages you visit, your device and browser, and your approximate location, so we can see how the site is used.',
                'Security: the web server keeps short technical logs (IP address, browser, time) to protect the site against abuse.',
            ],
            'paragraphs' => [
                'We use the details you send because you asked us to answer you or to book an appointment, and analytics only with your consent.',
                'Please don\'t send detailed medical information through the forms; we will ask for what we need during your consultation.',
            ],
        ],
        [
            'heading' => 'Cookies',
            'items' => [
                'Session and XSRF-TOKEN: needed for the forms to work securely. They expire after two hours.',
                'site_consent: remembers your cookie choice for one year.',
                'Google Analytics cookies (such as _ga): only after you click “Accept analytics”. You can change your choice at any time with “Cookie settings” at the bottom of every page.',
            ],
        ],
        [
            'heading' => 'Who receives your data',
            'items' => [
                'Layla Hanna and the clinic team, who answer your message or booking.',
                'Hostinger, which hosts the website and its database, and our email provider, which delivers the notification about your message to us.',
                'Google, only if you accept analytics cookies, or when you choose to show the map or play the video on this site.',
            ],
            'paragraphs' => [
                'We do not sell your data or share it for advertising.',
            ],
        ],
        [
            'heading' => 'How long we keep it',
            'items' => [
                'Contact messages and booking requests are deleted automatically :months months after they are sent, or earlier if you ask us.',
                'Analytics data is kept for the period set in our Google Analytics account.',
            ],
        ],
        [
            'heading' => 'Your rights',
            'paragraphs' => [
                'You can ask us at any time to see the data we hold about you, to correct it or to delete it, and you can withdraw your consent to analytics cookies. Write to :email. If you live in the European Union, you can also complain to your data protection authority.',
            ],
        ],
    ],
];
