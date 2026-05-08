<?php

declare(strict_types=1);

namespace Core;

final class Config
{
    /**
     * Loaded configuration files.
     *
     * @var array<string, mixed>
     */
    private static array $items = [];

    /**
     * Load config directory.
     */
    public static function load(string $path): void
    {
        $files = glob($path . '/*.php');

        if ($files === false) {
            return;
        }

        foreach ($files as $file) {

            $name = pathinfo($file, PATHINFO_FILENAME);

            self::$items[$name] = require $file;
        }
    }

    /**
     * Get configuration value.
     */
    public static function get(
        string $key,
        mixed $default = null
    ): mixed {

        $segments = explode('.', $key);

        $config = self::$items;

        foreach ($segments as $segment) {

            if (!isset($config[$segment])) {
                return $default;
            }

            $config = $config[$segment];
        }

        return $config;
    }
}