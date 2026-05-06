<?php

declare(strict_types=1);

namespace App\Middleware;

use Core\Request;
use Core\MiddlewareInterface;

/**
 * Example authentication middleware.
 *
 * Demonstrates:
 * - request interception
 * - middleware pipeline flow
 * - access control handling
 */
final class AuthMiddleware implements MiddlewareInterface
{
    /**
     * Handle the incoming request.
     */
    public function handle(
        Request $request,
        callable $next
    ): mixed {

        /*
         |--------------------------------------------------------------------------
         | Example Authentication Check
         |--------------------------------------------------------------------------
         |
         | Replace this logic with
         | a real authentication system.
         |
         */

        $loggedIn = true;

        if (!$loggedIn) {
            return 'Unauthorized';
        }

        return $next();
    }
}