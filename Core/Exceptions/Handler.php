<?php

declare(strict_types=1);

namespace Core\Exceptions;

use Throwable;
use Core\Request;
use Core\Response;

final readonly class Handler
{
    public function __construct(
        private Request $request,
        private Response $response,
    ) {}

    public function handle(Throwable $e): never
    {
        $status = $this->resolveStatusCode($e);

        http_response_code($status);

        if ($this->request->isApi()) {
            $this->response->json([
                'error' => $e->getMessage(),
                'status' => $status,
            ]);
        }

        if (ini_get('display_errors')) {
            echo $e->getMessage();
        } else {
            echo $this->defaultMessage($status);
        }

        exit;
    }

    private function resolveStatusCode(Throwable $e): int
    {
        $code = $e->getCode();

        return ($code >= 100 && $code <= 599)
            ? $code
            : 500;
    }

    private function defaultMessage(int $status): string
    {
        return match ($status) {
            404 => '404 Not Found',
            405 => '405 Method Not Allowed',
            default => 'Internal Server Error',
        };
    }
}