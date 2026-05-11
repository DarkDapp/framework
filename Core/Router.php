<?php

declare(strict_types=1);

namespace Core;

use Closure;
use Core\Exceptions\MethodNotAllowedException;
use Core\Exceptions\NotFoundException;

/**
 * HTTP routing engine.
 *
 * Responsible for:
 * - route registration
 * - route matching
 * - URI parameter extraction
 * - controller dispatching
 * - middleware execution pipeline
 */
final class Router
{
    /**
     * Registered application routes.
     *
     * @var array<int, RouteDefinition>
     */
    private array $routes = [];

    public function __construct(
        private readonly Container $container
    ) {}

    /**
     * Register GET route.
     */
    public function get(
        string $uri,
        callable|array $handler
    ): RouteDefinition {
        return $this->add('GET', $uri, $handler);
    }

    /**
     * Register POST route.
     */
    public function post(
        string $uri,
        callable|array $handler
    ): RouteDefinition {
        return $this->add('POST', $uri, $handler);
    }

    /**
     * Add route to collection.
     */
    public function add(
        string $method,
        string $uri,
        callable|array $handler
    ): RouteDefinition {

        $route = new RouteDefinition(
            strtoupper($method),
            $this->normalize($uri),
            $handler
        );

        $this->routes[] = $route;

        return $route;
    }

    /**
     * Normalize URI format.
     */
    private function normalize(string $uri): string
    {
        $uri = trim($uri, '/');

        return $uri === ''
            ? '/'
            : '/' . $uri;
    }

    /**
     * Dispatch incoming request.
     */
    public function dispatch(
        string $requestUri,
        string $requestMethod
    ): mixed {

        $requestUri = $this->normalize($requestUri);
        $requestMethod = strtoupper($requestMethod);

        $allowedMethods = [];

        foreach ($this->routes as $route) {

            $pattern = $this->convertToRegex($route->uri);

            if (!preg_match($pattern, $requestUri, $matches)) {
                continue;
            }

            $allowedMethods[] = $route->method;

            if ($route->method !== $requestMethod) {
                continue;
            }

            $params = array_filter(
                $matches,
                static fn($key): bool => !is_int($key),
                ARRAY_FILTER_USE_KEY
            );

            return $this->runMiddlewares(
                $route,
                fn() => $this->resolve(
                    $route->handler,
                    $params
                )
            );
        }

        if ($allowedMethods !== []) {
            throw new MethodNotAllowedException();
        }

        throw new NotFoundException();
    }

    /**
     * Convert route URI to regex pattern.
     *
     * Example:
     * /user/{id} => #^/user/(?P<id>[^/]+)$#
     */
    private function convertToRegex(string $uri): string
    {
        $pattern = preg_replace(
            '/{(\w+)}/',
            '(?P<$1>[^/]+)',
            $uri
        );

        return "#^{$pattern}$#";
    }

    /**
     * Resolve route handler.
     */
    private function resolve(
        array|Closure $handler,
        array $params
    ): mixed {

        /**
         * Controller action.
         */
        if (is_array($handler)) {

            [$class, $method] = $handler;

            if (!class_exists($class)) {
                throw new NotFoundException(
                    "Controller not found: {$class}"
                );
            }

            $controller = $this->container->get($class);

            if (!method_exists($controller, $method)) {
                throw new NotFoundException(
                    "Method not found: {$method}"
                );
            }

            return $controller->$method(...$params);
        }

        /**
         * Closure route.
         */
        return $handler(...$params);
    }

    /**
     * Execute middleware pipeline.
     */
    private function runMiddlewares(
        RouteDefinition $route,
        callable $destination
    ): mixed {

        $pipeline = array_reverse(
            $route->middlewares()
        );

        $next = $destination;

        foreach ($pipeline as $middleware) {

            $next = function () use (
                $middleware,
                $next
            ) {

                $instance = $this->container->get($middleware);

                return $instance->handle(
                    $this->container->get(Request::class),
                    $next
                );
            };
        }

        return $next();
    }
}
