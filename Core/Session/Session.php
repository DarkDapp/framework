<?php

declare(strict_types=1);

namespace Core\Session;

use InvalidArgumentException;

/**
 * Session data manager.
 */
final class Session
{
    /**
     * Store session value.
     */
    public static function set(
        string $key,
        mixed $value
    ): void {

        self::validate($key);

        $_SESSION[$key] = $value;
    }

    /**
     * Retrieve session value.
     */
    public static function get(
        string $key,
        mixed $default = null
    ): mixed {

        self::validate($key);

        return $_SESSION[$key] ?? $default;
    }

    /**
     * Determine if key exists.
     */
    public static function has(string $key): bool
    {
        self::validate($key);

        return array_key_exists($key, $_SESSION);
    }

    /**
     * Remove session key.
     */
    public static function forget(string $key): void
    {
        self::validate($key);

        unset($_SESSION[$key]);
    }

    /**
     * Destroy current session securely.
     */
    public static function destroy(): void
    {
        $_SESSION = [];

        if (ini_get('session.use_cookies')) {

            $params = session_get_cookie_params();

            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params['path'],
                $params['domain'],
                $params['secure'],
                $params['httponly']
            );
        }

        session_destroy();
    }

    /**
     * Regenerate session ID.
     */
    public static function regenerate(): void
    {
        session_regenerate_id(true);
    }

    /**
     * Store flash data.
     */
    public static function flash(
        string $key,
        mixed $value
    ): void {

        self::validate($key);

        $_SESSION['_flash'][$key] = $value;
    }

    /**
     * Retrieve flashed data once.
     */
    public static function old(
        string $key,
        mixed $default = null
    ): mixed {

        self::validate($key);

        $value = $_SESSION['_flash'][$key]
            ?? $default;

        unset($_SESSION['_flash'][$key]);

        return $value;
    }

    /**
     * Validate session key.
     */
    private static function validate(
        string $key
    ): void {

        $key = trim($key);

        if ($key === '') {
            throw new InvalidArgumentException(
                'Session key cannot be empty.'
            );
        }

        if (str_contains($key, "\0")) {
            throw new InvalidArgumentException(
                'Invalid session key.'
            );
        }
    }
}
