<?php

declare(strict_types=1);

use Core\Env;

return [

    'host' => Env::get('DB_HOST'),

    'port' => Env::get('DB_PORT'),

    'database' => Env::get('DB_NAME'),

    'username' => Env::get('DB_USER'),

    'password' => Env::get('DB_PASS'),

];
