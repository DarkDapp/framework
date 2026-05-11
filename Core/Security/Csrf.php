<?php

declare(strict_types=1);

namespace Core\Security;

use Core\Session\Session;
use Random\RandomException;
use RuntimeException;

/**
 * CSRF token manager.
 */
final class Csrf
{
    /**
     * Session storage key.
     */
    private const string KEY = '_csrf_token';

    /**
     * Generate or retrieve token.
     */
    public static function token(): string
    {
        if (!Session::has(self::KEY)) {

            try {

                $token = bin2hex(
                    random_bytes(32)
                );

            } catch (RandomException $e) {

                throw new RuntimeException(
                    'Unable to generate CSRF token.',
                    0,
                    $e
                );
            }

            Session::set(
                self::KEY,
                $token
            );
        }

        return (string) Session::get(self::KEY);
    }

    /**
     * Validate CSRF token.
     */
    public static function verify(
        ?string $token
    ): bool {

        if ($token === null || $token === '') {
            return false;
        }

        return hash_equals(
            (string) Session::get(self::KEY),
            $token
        );
    }

    /**
     * Generate hidden form field.
     */
    public static function field(): string
    {
        return sprintf(
            '<input type="hidden" name="_token" value="%s">',
            htmlspecialchars(
                self::token(),
                ENT_QUOTES,
                'UTF-8'
            )
        );
    }
}