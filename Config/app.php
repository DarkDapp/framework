<?php

declare(strict_types=1);

use Core\Env;

return [
    'name' => Env::get('APP_NAME', 'DarkDapp'),
    'debug' => Env::get('APP_DEBUG', false),
    'timezone' => 'UTC',
];