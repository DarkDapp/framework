<?php

declare(strict_types=1);

namespace Core;

use DateTime;

final class Logger
{
    /**
     * Write info log.
     */
    public static function info(string $message): void
    {
        self::write('INFO', $message);
    }

    /**
     * Write warning log.
     */
    public static function warning(string $message): void
    {
        self::write('WARNING', $message);
    }

    /**
     * Write error log.
     */
    public static function error(string $message): void
    {
        self::write('ERROR', $message);
    }

    /**
     * Write debug log.
     */
    public static function debug(string $message): void
    {
        self::write('DEBUG', $message);
    }

    /**
     * Get log file path.
     */
    private static function path(): string
    {
        return dirname(__DIR__) . '/storage/logs/app.log';
    }

    /**
     * Write log entry.
     */
    private static function write(
        string $level,
        string $message
    ): void {

        $file = self::path();
        $directory = dirname($file);

        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        $date = new DateTime();

        $formatted = sprintf(
            "[%s] %s: %s%s",
            $date->format('Y-m-d H:i:s'),
            $level,
            $message,
            PHP_EOL
        );

        @file_put_contents(
            $file,
            $formatted,
            FILE_APPEND | LOCK_EX
        );
    }
}
