<?php

declare(strict_types=1);

namespace Core;

use App\Providers\AppServiceProvider;
use App\Providers\RouteServiceProvider;

/**
 * Framework bootstrap kernel.
 *
 * Responsible for:
 * - creating the application instance
 * - registering core service providers
 */
final class Kernel
{
    /**
     * Create the application instance.
     */
    public static function make(): Application
    {
        return new Application([
            AppServiceProvider::class,
            RouteServiceProvider::class,
        ]);
    }
}