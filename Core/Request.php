<?php

declare(strict_types=1);

namespace Core;

/**
 * HTTP request abstraction.
 *
 * Responsible for:
 * - request path resolution
 * - HTTP method detection
 * - query parameter access
 * - input data access
 */
final class Request
{
    private string $path;
    private string $method;

    public function __construct()
    {
        $this->path = $this->resolvePath();
        $this->method = $this->resolveMethod();
    }

    /**
     * Get the normalized request path.
     */
    public function path(): string
    {
        return $this->path;
    }

    /**
     * Get the current HTTP method.
     */
    public function method(): string
    {
        return $this->method;
    }

    /**
     * Determine whether the request
     * targets the API layer.
     */
    public function isApi(): bool
    {
        return str_starts_with($this->path, '/api');
    }

    /**
     * Resolve the current request URI.
     */
    private function resolvePath(): string
    {
        $path = $_SERVER['REQUEST_URI'] ?? '/';

        $path = parse_url(
            $path,
            PHP_URL_PATH
        ) ?: '/';

        $path = trim($path, '/');

        return $path === ''
            ? '/'
            : '/' . $path;
    }

    /**
     * Resolve the HTTP request method.
     */
    private function resolveMethod(): string
    {
        return strtoupper(
            $_SERVER['REQUEST_METHOD'] ?? 'GET'
        );
    }

    /**
     * Retrieve a query string parameter.
     */
    public function query(
        string $key,
        mixed $default = null
    ): mixed {
        return $_GET[$key] ?? $default;
    }

    /**
     * Retrieve input data from POST requests.
     */
    public function input(
        string $key,
        mixed $default = null
    ): mixed {
        return $_POST[$key] ?? $default;
    }
}
