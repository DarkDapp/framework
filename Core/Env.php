<?php

declare(strict_types=1);

namespace Core;

final class Env
{
    /**
     * Loaded environment variables.
     *
     * @var array<string, string>
     */
    private static array $variables = [];

    /**
     * Load .env file.
     */
    public static function load(string $path): void
    {
        if (!is_file($path)) {
            return;
        }

        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        if ($lines === false) {
            return;
        }

        foreach ($lines as $line) {

            $line = trim($line);

            if ($line === '' || str_starts_with($line, '#')) {
                continue;
            }

            [$key, $value] = array_pad(
                explode('=', $line, 2),
                2,
                ''
            );

            $key = trim($key);
            $value = trim($value);

            self::$variables[$key] = $value;

            $_ENV[$key] = $value;
        }
    }

    /**
     * Get environment value.
     */
    public static function get(
        string $key,
        mixed $default = null
    ): mixed {
        return self::$variables[$key]
            ?? $_ENV[$key]
            ?? $default;
    }
}
