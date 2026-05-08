<?php

declare(strict_types=1);

namespace Core\Exceptions;

use Core\Logger;
use Core\Request;
use Throwable;

final class Handler
{
    /**
     * Render exception response.
     */
    public function render(
        Request $request,
        Throwable $e
    ): never {

        $status = $this->status($e);

        http_response_code($status);

        $this->report($e);

        if ($request->isApi()) {
            $this->renderJson($e, $status);
        }

        $this->renderHtml($e, $status);
    }

    /**
     * Report exception to log file.
     */
    private function report(Throwable $e): void
    {
        Logger::error(sprintf(
            '%s in %s:%d',
            $e->getMessage(),
            $e->getFile(),
            $e->getLine()
        ));
    }

    /**
     * Render JSON error response.
     */
    private function renderJson(
        Throwable $e,
        int $status
    ): never {

        header('Content-Type: application/json; charset=utf-8');

        echo json_encode([
            'error' => $e->getMessage(),
            'status' => $status,
        ]);

        exit;
    }

    /**
     * Render HTML error response.
     */
    private function renderHtml(
        Throwable $e,
        int $status
    ): never {

        $debug = (bool) ($_ENV['APP_DEBUG'] ?? false);

        if ($debug) {

            echo <<<HTML
            <h1>Exception</h1>
            <p><strong>Message:</strong> {$e->getMessage()}</p>
            <p><strong>File:</strong> {$e->getFile()}</p>
            <p><strong>Line:</strong> {$e->getLine()}</p>
            HTML;

            exit;
        }

        echo match ($status) {
            404 => '404 | Page Not Found',
            405 => '405 | Method Not Allowed',
            default => '500 | Internal Server Error',
        };

        exit;
    }

    /**
     * Resolve HTTP status code.
     */
    private function status(Throwable $e): int
    {
        if ($e instanceof HttpException) {
            return $e->status();
        }

        return 500;
    }
}