<?php

declare(strict_types=1);

namespace App\Controllers;

use Core\Session\Session;
/**
 * Example application controller.
 *
 * Demonstrates:
 * - basic route handling
 * - route parameter usage
 * - text and JSON responses
 */
final class HomeController
{
    /**
     * Handle the home page route.
     */
    public function index(): string
    {
        Session::set('message', 'DarkDApp');

        return view('home', [
            'title' => Session::get('message')
        ]);
    }

    /**
     * Display a user resource example.
     */
    public function user(string $id): array
    {
        return [
            'user_id' => $id,
        ];
    }
}