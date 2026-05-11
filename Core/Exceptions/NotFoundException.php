<?php

declare(strict_types=1);

namespace Core\Exceptions;

final class NotFoundException extends HttpException
{
    public function __construct(
        string $message = 'Page Not Found'
    ) {
        parent::__construct($message, 404);
    }
}
