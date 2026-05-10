<?php

declare(strict_types=1);

namespace Core;

use RuntimeException;

/**
 * Lightweight PHP view renderer.
 */
final class View
{
    /**
     * Render view file.
     */
    public static function make(
        string $view,
        array $data = [],
        string $layout = 'layouts.app'
    ): string {

        $viewPath = self::path($view);

        if (!is_file($viewPath)) {
            throw new RuntimeException(
                "View not found: $view"
            );
        }

        extract($data, EXTR_SKIP);

        ob_start();

        require $viewPath;

        $content = (string) ob_get_clean();

        $layoutPath = self::path($layout);

        if (!is_file($layoutPath)) {
            return $content;
        }

        ob_start();

        require $layoutPath;

        $layoutContent = (string) ob_get_clean();

        return str_replace(
            '{{content}}',
            $content,
            $layoutContent
        );
    }

    /**
     * Resolve view path.
     */
    private static function path(string $view): string
    {
        if (str_contains($view, '..')) {
            throw new RuntimeException(
                'Invalid view path.'
            );
        }

        $view = str_replace('.', '/', $view);

        return dirname(__DIR__)
            . '/App/Views/'
            . $view
            . '.php';
    }
}