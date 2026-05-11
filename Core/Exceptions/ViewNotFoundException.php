<?php

declare(strict_types=1);

namespace Core\Exceptions;

final class ViewNotFoundException extends HttpException
{
    public function __construct(
        string $view
    ) {
        parent::__construct(
            "View not found: $view"
        );
    }
}
