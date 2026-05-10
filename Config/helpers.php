<?php

declare(strict_types=1);

use Core\View;

if (!function_exists('e')) {
    function e($value): string {
        return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
    }
}
if (!function_exists('config')) {

    function config(
        string $key,
        mixed $default = null
    ): mixed {
        return Core\Config::get($key, $default);
    }
}
if (!function_exists('view')) {

    function view(
        string $view,
        array $data = []
    ): string {
        return View::make($view, $data);
    }
}