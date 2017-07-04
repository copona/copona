<?php

return [
    'install' => [

        'db_autostart'   => false,

        /**
         * Actions
         */
        'action_default' => 'install/step_1',
        'action_router'  => 'startup/router',
        'action_error'   => 'error/not_found',

        'action_pre_action' => [
            'startup/language',
            'startup/upgrade',
            'startup/database'
        ]
    ]
];