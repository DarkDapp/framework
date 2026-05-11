<?php

declare(strict_types=1);

/**
 * ---------------------------------------------------------
 * Framework Bootstrap
 * ---------------------------------------------------------
 *
 * Registers the framework autoloader
 * and initializes namespace mappings.
 *
 * This file prepares the environment
 * before the application kernel starts.
 *
 */

require_once __DIR__ . '/Core/AutoLoader.php';
require_once __DIR__ . '/Config/helpers.php';

/*
|--------------------------------------------------------------------------
| Register Autoloader
|--------------------------------------------------------------------------
*/

Core\AutoLoader::register();

/*
|--------------------------------------------------------------------------
| Namespace Registration
|--------------------------------------------------------------------------
|
| Maps namespaces to their base directories.
|
*/

Core\AutoLoader::addNamespace(
    'Core',
    __DIR__ . '/Core'
);

Core\AutoLoader::addNamespace(
    'App',
    __DIR__ . '/App'
);

Core\AutoLoader::addNamespace(
    'Config',
    __DIR__ . '/Config'
);

/*
|--------------------------------------------------------------------------
| Load Environment Variables
|--------------------------------------------------------------------------
*/

Core\Env::load(__DIR__ . '/.env');

/*
|--------------------------------------------------------------------------
| Load Configuration Files
|--------------------------------------------------------------------------
*/

Core\Config::load(__DIR__ . '/Config');

/*
|--------------------------------------------------------------------------
| Start Secure Session
|--------------------------------------------------------------------------
*/

$session = new Core\Session\SessionManager(
    __DIR__ . '/storage/sessions'
);

$session->start();
