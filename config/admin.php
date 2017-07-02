<?php

return [
    'admin' => [

        'db_autostart'      => true,

        /**
         * Pre actions
         */
        'action_pre_action' => [
            'startup/startup',
            'startup/error',
            'startup/event',
            'startup/sass',
            'startup/login',
            'startup/permission'
        ],

        /**
         * Actions
         */
        'action_default'    => 'common/dashboard',
        'action_router'     => 'startup/router',
        'action_error'      => 'error/not_found',
    ]
];