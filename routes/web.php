<?php

declare(strict_types=1);

use App\Middleware\AuthMiddleware;
use Core\Route;
use App\Controllers\HomeController;

Route::get('/', [HomeController::class, 'index']);

Route::get('/user/{id}', [
    HomeController::class,
    'user'
]);

Route::get('/admin', function () {
    return 'Admin Panel';
})
    ->middleware(AuthMiddleware::class);

use App\Middleware\CsrfMiddleware;

Route::post('/profile', function () {

    return 'Saved';

})->middleware(CsrfMiddleware::class);