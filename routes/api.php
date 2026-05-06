<?php

declare(strict_types=1);

use Core\Route;

Route::get('/api/users', function () {
    return [
        'users' => []
    ];
});