<?php

declare(strict_types=1);

namespace App\Providers;

use Core\Route;
use Core\Router;
use Core\ServiceProvider;

/**
 * Route service provider.
 *
 * Responsible for:
 * - bootstrapping the router
 * - loading application routes
 * - binding route definitions
 */
final class RouteServiceProvider extends ServiceProvider
{
    /**
     * Register application routes.
     */
    public function register(): void
    {
        $router = $this->container->get(Router::class);

        Route::setRouter($router);

        $web = dirname(__DIR__, 2) . '/routes/web.php';
        $api = dirname(__DIR__, 2) . '/routes/api.php';

        if (is_file($web)) {
            require $web;
        }

        if (is_file($api)) {
            require $api;
        }
    }
}