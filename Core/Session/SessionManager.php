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

        $handler = new FileSessionHandler(
            $this->path,
            $this->name
        );

        session_set_save_handler($handler, true);
        session_name($this->name);
        session_save_path($this->path);

        session_start([
            'cookie_lifetime' => $this->lifetime,
            'cookie_secure'   => true,
            'cookie_httponly' => true,
            'cookie_samesite' => 'Strict',
            'use_strict_mode' => true,
        ]);
    }
}
