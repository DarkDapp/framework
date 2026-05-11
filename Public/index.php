<?php

declare(strict_types=1);

/**
 * ---------------------------------------------------------
 * DarkDapp Framework
 * ---------------------------------------------------------
 *
 * Application entry point.
 *
 * This file is responsible for:
 * - loading the bootstrap process
 * - creating the application kernel
 * - starting the HTTP lifecycle
 *
 */

require_once dirname(__DIR__) . '/bootstrap.php';

$app = Core\Kernel::make();

$app->run();
