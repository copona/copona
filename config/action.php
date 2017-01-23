<?php

return [

    'action' => [

        // Actions
        'action_router'     => 'startup/router',
        'action_error'      => 'error/not_found',
        'action_pre_action' => [],
        'action_event'      => [],

        // Catalog
        'catalog'           => [

            'action_default' => 'common/home',

            'action_pre_action' => [
                'startup/session',
                'startup/startup',
                'startup/error',
                'startup/event',
                'startup/maintenance',
                'startup/seo_url'
            ],

            'action_event' => [
                'view/*/before' => 'event/theme',
                //'controller/*/before' => 'event/debug/before',
                //'controller/*/after'  => 'event/debug/after'
            ],
        ],

        // Admin
        'admin'             => [

            'action_default' => 'common/dashboard',

            'action_pre_action' => [
                'startup/startup',
                'startup/error',
                'startup/event',
                'startup/sass',
                'startup/login',
                'startup/permission'
            ],

            'action_event' => [
                'view/*/before' => 'event/theme'
            ],
        ]
    ]
];