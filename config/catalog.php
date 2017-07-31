<?php

return [
    'catalog' => [

        'db_autostart'      => true,

        /**
         * Pre actions
         */
        'action_pre_action' => [
            'startup/session',
            'startup/startup',
            'startup/error',
            'startup/event',
            'startup/maintenance',
            'startup/seo_url'
        ],

        /**
         * Actions
         */
        'action_default'    => 'common/home',
        'action_router'     => 'startup/router',
        'action_error'      => 'error/not_found',

        /**
         * Action Events
         */
        'action_event'      => [],
    ]
];