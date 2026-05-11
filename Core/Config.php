<?php

declare(strict_types=1);

namespace Core;

/**
 * Lightweight configuration manager.
 */
final class Config
{
    /**
     * Loaded configuration items.
     *
     * @var array<string, mixed>
     */
    private static array $items = [];

    /**
     * Load configuration directory.
     */
    public static function load(string $path): void
    {
        $files = glob(rtrim($path, '/') . '/*.php');

        if ($files === false) {
            return;
        }

        foreach ($files as $file) {

            $name = pathinfo(
                $file,
                PATHINFO_FILENAME
            );

            self::$items[$name] = require $file;
        }
    }

    /**
     * Get configuration value using dot notation.
     */
    public static function get(
        string $key,
        mixed $default = null
    ): mixed {

        $segments = explode('.', $key);

        $config = self::$items;

        foreach ($segments as $segment) {

            if (
                !is_array($config)
                || !array_key_exists($segment, $config)
            ) {
                return $default;
            }

            $config = $config[$segment];
        }

        return $config;
    }

    /**
     * Determine if config key exists.
     */
    public static function has(string $key): bool
    {
        return self::get($key) !== null;
    }

    /**
     * Get all loaded configuration.
     */
    public static function all(): array
    {
        return self::$items;
    }
}
