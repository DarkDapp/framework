<?php

declare(strict_types=1);

use Core\Env;

return [

    'name' => Env::get('APP_NAME', 'DarkDApp'),

    'env' => Env::get('APP_ENV', 'production'),

    'debug' => filter_var(
        Env::get('APP_DEBUG', false),
        FILTER_VALIDATE_BOOL
    ),

    'url' => Env::get('APP_URL'),

    'secure' => Env::get(
        'SESSION_SECURE_COOKIE',
        true
    )

];