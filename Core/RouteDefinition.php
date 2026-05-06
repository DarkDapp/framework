<?php

declare(strict_types=1);

namespace Core;

use Closure;

/**
 * Route definition object.
 *
 * Stores:
 * - HTTP method
 * - route URI
 * - route handler
 * - middleware pipeline
 */
final class RouteDefinition
{
    private array $middleware = [];

    public function __construct(
        public readonly string $method,
        public readonly string $uri,
        public readonly Closure|array $handler
    ) {}

    /**
     * Attach middleware
     */
    public function middleware(string $middleware): self
    {
        $this->middleware[] = $middleware;

        return $this;
    }

    /**
     * Get middlewares
     */
    public function middlewares(): array
    {
        return $this->middleware;
    }
}