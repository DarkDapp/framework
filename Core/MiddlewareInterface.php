<?php

declare(strict_types=1);

namespace Core;

/**
 * Middleware contract.
 *
 * Every middleware must implement
 * a request handler pipeline.
 */
interface MiddlewareInterface
{
    /**
     * Handle an incoming request.
     */
    public function handle(
        Request $request,
        callable $next
    ): mixed;
}
