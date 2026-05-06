<?php

declare(strict_types=1);

namespace Core;

use Closure;
use LogicException;
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
    private array $routes = [];
    private Container $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }
    public function get(
        string $uri,
        callable|array $handler
    ): RouteDefinition {
        return $this->add('GET', $uri, $handler);
    }
    public function post(
        string $uri,
        callable|array $handler
    ): RouteDefinition {
        return $this->add('POST', $uri, $handler);
    }
    /**
     * Add route
     */
    public function add(
        string $method,
        string $uri,
        callable|array $handler
    ): RouteDefinition {

        $route = new RouteDefinition(
            $method,
            $this->normalize($uri),
            $handler
        );

        $this->routes[] = $route;

        return $route;
    }
    /**
     * Normalize URI
     */
    private function normalize(string $uri): string
    {
        $uri = trim($uri, '/');
        return $uri === '' ? '/' : '/' . $uri;
    }
    /**
     * Dispatch request
     */
    public function dispatch(
        string $requestUri,
        string $requestMethod
    ): mixed {

        $requestUri = $this->normalize($requestUri);

        foreach ($this->routes as $route) {

            if ($route->method !== $requestMethod) {
                continue;
            }

            $pattern = $this->convertToRegex($route->uri);

            if (preg_match($pattern, $requestUri, $matches)) {

                $params = array_filter(
                    $matches,
                    fn($key) => !is_int($key),
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
        }

        throw new NotFoundException();
    }
    /**
     * Convert /user/{id} → regex
     */
    private function convertToRegex(string $uri): string
    {
        $pattern = preg_replace(
            '/{([a-zA-Z0-9_]+)}/',
            '(?P<$1>[^/]+)',
            $uri
        );

        return "#^" . $pattern . "$#";
    }
    /**
     * Resolve handler
     */
    private function resolve(array|Closure $handler, array $params): mixed
    {
        if (is_array($handler)) {
            [$class, $method] = $handler;

            if (!class_exists($class)) {
                throw new LogicException("Controller not found: $class");
            }

            $controller = $this->container->get($class);

            if (!method_exists($controller, $method)) {
                throw new LogicException("Method not found: $method");
            }

            return $controller->$method(...$params);
        }

        return $handler(...$params);
    }
    private function runMiddlewares(
        RouteDefinition $route,
        callable $destination
    ): mixed {

        $pipeline = array_reverse($route->middlewares());

        $next = $destination;

        foreach ($pipeline as $middleware) {

            $next = function () use ($middleware, $next) {

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