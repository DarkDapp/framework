<?php

declare(strict_types=1);

namespace Core;

/**
 * PSR-4 style class autoloader.
 *
 * Responsible for:
 * - namespace registration
 * - class resolution
 * - automatic file loading
 *
 * Example:
 * Core\Controller\UserController
 * => Core/Controller/UserController.php
 */
final class AutoLoader
{
    /**
     * Registered namespace prefixes.
     *
     * @var array<string, array<int, string>>
     */
    private static array $prefixes = [];

    /**
     * Register the autoloader.
     */
    public static function register(): void
    {
        spl_autoload_register([self::class, 'load']);
    }

    /**
     * Register a namespace mapping.
     */
    public static function addNamespace(
        string $prefix,
        string $baseDir
    ): void {
        $prefix = trim($prefix, '\\') . '\\';

        $baseDir = rtrim(
                $baseDir,
                DIRECTORY_SEPARATOR
            ) . DIRECTORY_SEPARATOR;

        self::$prefixes[$prefix][] = $baseDir;
    }

    /**
     * Load a class file dynamically.
     */
    public static function load(string $className): void
    {
        foreach (self::$prefixes as $prefix => $baseDirs) {

            if (!str_starts_with($className, $prefix)) {
                continue;
            }

            $relativeClass = substr(
                $className,
                strlen($prefix)
            );

            foreach ($baseDirs as $baseDir) {

                $file = $baseDir
                    . str_replace(
                        '\\',
                        DIRECTORY_SEPARATOR,
                        $relativeClass
                    )
                    . '.php';

                if (is_readable($file)) {
                    require_once $file;
                    return;
                }
            }
        }
    }
}
