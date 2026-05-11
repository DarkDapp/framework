<?php

declare(strict_types=1);

namespace Core\Exceptions;

use Exception;

class HttpException extends Exception
{
    public function __construct(
        string $message = 'HTTP Error',
        int $statusCode = 500
    ) {
        parent::__construct($message, $statusCode);
    }

    public function status(): int
    {
        return $this->getCode();
    }
}
