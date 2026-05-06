# DarkDapp Framework

A modern lightweight PHP MVC framework built for performance, simplicity, and extensibility.

---

# Overview

DarkDapp Framework is a modern PHP framework designed with a clean architecture and zero external dependencies.

The framework focuses on:

- Performance
- Simplicity
- Extensibility
- Modern PHP 8.2 features
- Clean MVC architecture

---

# Features

- Lightweight MVC Architecture
- PSR-4 Style Autoloader
- Dependency Injection Container
- Service Providers
- Router with Dynamic Parameters
- Middleware Pipeline
- Request & Response Abstractions
- Automatic Dependency Resolution
- JSON & HTML Responses
- PHP 8.2+ Support
- Zero External Dependencies

---

# Requirements

- PHP 8.2+
- Apache or Nginx
- mod_rewrite enabled

---

# Installation

Clone the repository:

```bash
git clone https://github.com/DarkDapp/framework.git
```

Move into the project directory:

```bash
cd framework
```

Point your web server to:

```txt
/Public
```

---

# Project Structure

```txt
App/
├── Controllers/
├── Middleware/
└── Providers/

Core/
├── Application.php
├── AutoLoader.php
├── Container.php
├── Kernel.php
├── MiddlewareInterface.php
├── Request.php
├── Response.php
├── Route.php
├── RouteDefinition.php
├── Router.php
└── ServiceProvider.php

routes/
├── web.php
└── api.php

Public/
└── index.php
```

---

# Application Lifecycle

Application entry point:

```php
<?php

declare(strict_types=1);

require dirname(__DIR__) . '/bootstrap.php';

$app = Core\Kernel::make();
$app->run();
```

---

# Bootstrap

Registers the autoloader and namespaces.

```php
<?php

declare(strict_types=1);

require_once __DIR__ . '/Core/AutoLoader.php';

Core\AutoLoader::register();

Core\AutoLoader::addNamespace('Core', __DIR__ . '/Core');
Core\AutoLoader::addNamespace('App', __DIR__ . '/App');
Core\AutoLoader::addNamespace('Config', __DIR__ . '/Config');
```

---

# Routing

Basic route:

```php
Route::get('/', function () {
    return 'Hello World';
});
```

Controller route:

```php
Route::get('/user/{id}', [
    UserController::class,
    'show'
]);
```

Dynamic route parameters are automatically injected.

---

# Middleware

Attach middleware to routes:

```php
Route::get('/admin', function () {
    return 'Admin Panel';
})->middleware(AuthMiddleware::class);
```

Middleware example:

```php
final class AuthMiddleware implements MiddlewareInterface
{
    public function handle(
        Request $request,
        callable $next
    ): mixed {

        if (!$authenticated) {
            return 'Unauthorized';
        }

        return $next();
    }
}
```

---

# Dependency Injection Container

Services are automatically resolved using reflection.

Example:

```php
final class UserController
{
    public function __construct(
        private UserService $service
    ) {}
}
```

The framework automatically resolves dependencies from the container.

---

# Service Providers

Service providers register application services.

Example:

```php
final class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->container->singleton(
            Request::class,
            fn() => new Request()
        );
    }
}
```

---

# Request

Access request data easily:

```php
$request->path();
$request->method();
$request->query('id');
$request->input('email');
```

---

# Response

Return text:

```php
return 'Hello';
```

Return JSON:

```php
return [
    'success' => true
];
```

Redirect:

```php
$response->redirect('/login');
```

---

# Container

The container supports:

- bind()
- singleton()
- automatic class resolution

Example:

```php
$container->singleton(
    UserService::class,
    fn() => new UserService()
);
```

---

# Router

Supports:

- GET routes
- POST routes
- Route parameters
- Middleware pipelines
- Controller dispatching

Example:

```php
Route::get('/post/{id}', [
    PostController::class,
    'show'
]);
```

---

# Documentation

Official documentation:

https://docs.darkdapp.com

---

# Philosophy

DarkDapp Framework focuses on:

- Minimalism
- Clean architecture
- Modern PHP standards
- Developer experience
- High performance
- Easy extensibility

Without unnecessary complexity or dependency bloat.

---

# License

MIT License