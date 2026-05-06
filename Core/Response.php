<?php

declare(strict_types=1);

namespace Core;

use JsonException;

/**
 * HTTP response manager.
 *
 * Responsible for:
 * - status codes
 * - JSON responses
 * - redirects
 * - text responses
 * - automatic response formatting
 */
final class Response
{
    /**
     * Set HTTP status
     */
    public function status(int $code): self
    {
        http_response_code($code);
        return $this;
    }

    /**
     * JSON response
     */
    public function json(mixed $data): never
    {
        header('Content-Type: application/json; charset=utf-8');

        try {
            echo json_encode(
                $data,
                JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR
            );
        } catch (JsonException) {
            http_response_code(500);
            echo json_encode(['error' => 'Internal Server Error']);
        }

        exit;
    }

    /**
     * Redirect
     */
    public function redirect(string $url, int $status = 302): never
    {
        header('Location: ' . $url, true, $status);
        exit;
    }
    public function text(mixed $content): void
    {
        header('Content-Type: text/html; charset=utf-8');
        echo $content;
    }
    public function send(mixed $data): void
    {
        match (true) {
            is_array($data),
            is_object($data) => $this->json($data),

            default => $this->text($data),
        };
    }
}