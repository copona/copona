<?php

return [

    'demo' => [

        /**
         * Public demo mode
         *
         * Makes the admin read-only (no saves, deletes, uploads or installs),
         * stops outgoing e-mail and catalog file uploads, and shows a banner
         * saying the store is a demo that resets regularly.
         * See deploy/demo/README.md.
         */
        'mode'           => env('DEMO_MODE', false),

        /**
         * Credentials pre-filled on the admin login form in demo mode
         */
        'admin_username' => env('DEMO_ADMIN_USERNAME', ''),
        'admin_password' => env('DEMO_ADMIN_PASSWORD', ''),

        /**
         * How often the demo database is reset, shown in the banner
         */
        'reset_interval' => env('DEMO_RESET_INTERVAL', 'every hour'),
    ]
];
