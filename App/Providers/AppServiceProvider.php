<?php

declare(strict_types=1);

namespace App\Providers;

use Core\Request;
use Core\Response;
use Core\Router;
use Core\ServiceProvider;
use Core\Config;
/**
 * Main application service provider.
 *
 * Responsible for registering
 * core framework services
 * into the container.
 */
final class AppServiceProvider extends ServiceProvider
{
    /**
     * Register application services.
     */
    public function register(): void
    {
        $this->container->singleton(
            Request::class,
            fn() => new Request()
        );

        $this->container->singleton(
            Response::class,
            fn() => new Response()
        );

        $this->container->singleton(
            Router::class,
            fn($container) => new Router($container)
        );

        $this->container->singleton(
            Config::class,
            fn() => new Config()
        );
    }
}
