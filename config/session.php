<?php

return [
    'session' => [

        'adapter' => \Copona\Core\System\Library\Session\Adapters\Native::class,

        'session_autostart' => true,
        'session_name' => 'PHPSESSID',
    ]
];