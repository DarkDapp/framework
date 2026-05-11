<?php

declare(strict_types=1);

namespace App\Middleware;

use Core\Request;
use Core\Security\Csrf;
use Core\Exceptions\HttpException;
use Core\MiddlewareInterface;

/**
 * CSRF protection middleware.
 */
final class CsrfMiddleware
    implements MiddlewareInterface
{
    /**
     * @throws HttpException
     */
    public function handle(
        Request $request,
        callable $next
    ): mixed {

        if ($request->method() !== 'POST') {
            return $next();
        }

        $token = $request->input('_token');

        if (!Csrf::verify($token)) {

            throw new HttpException(
                'CSRF token mismatch.',
                419
            );
        }

        return $next();
    }
}
