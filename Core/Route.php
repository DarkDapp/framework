<?php

declare(strict_types=1);

namespace Core;

use Closure;
use Core\Exceptions\RouterNotSetException;

/**
 * Static route registration API.
 *
 * Provides a clean interface
 * for registering application routes.
 */
final class Route
{
    private static ?Router $router = null;

    /**
     * Set router instance
     */
    public static function setRouter(Router $router): void
    {
        self::$router = $router;
    }

    private static function router(): Router
    {
        if (self::$router === null) {
            throw new RouterNotSetException('Router has not been set.');
        }

        return self::$router;
    }

    /**
     * Register GET route
     */
    public static function get(
        string $uri,
        Closure|array $handler
    ): RouteDefinition {
        return self::router()->get($uri, $handler);
    }

    /**
     * Register POST route
     */
    public static function post(
        string $uri,
        Closure|array $handler
    ): RouteDefinition {
        return self::router()->post($uri, $handler);
    }
}
