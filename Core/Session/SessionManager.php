<?php

declare(strict_types=1);

namespace Core\Session;

/**
 * Secure session bootstrap manager.
 */
final readonly class SessionManager
{
    public function __construct(
        private string $path,
        private string $name = 'darkdapp_session',
        private int    $lifetime = 7200,
    ) {}

    /**
     * Start secure session.
     */
    public function start(): void
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            return;
        }

        $this->configure();

        $handler = new FileSessionHandler(
            $this->path,
            $this->name
        );

        session_set_save_handler($handler, true);

        session_name($this->name);

        session_save_path($this->path);

        session_start([
            'cookie_lifetime' => $this->lifetime,
            'use_strict_mode' => true,
        ]);
    }

    /**
     * Configure secure session cookies.
     */
    private function configure(): void
    {
        session_set_cookie_params([
            'lifetime' => $this->lifetime,
            'path' => '/',
            'domain' => '',
            'secure' => $this->isSecure(),
            'httponly' => true,
            'samesite' => 'Lax',
        ]);
    }

    /**
     * Detect HTTPS.
     */
    private function isSecure(): bool
    {
        return
            (!empty($_SERVER['HTTPS']))
            && $_SERVER['HTTPS'] !== 'off';
    }
}