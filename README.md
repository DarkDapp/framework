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

## Environment & Configuration

DarkDapp Framework includes a lightweight environment and configuration system built without external dependencies.

---

### Environment File

Create a `.env` file in the project root:

```env
APP_NAME=DarkDapp
APP_DEBUG=true

DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=darkdapp
DB_USERNAME=root
DB_PASSWORD=
```

---

### Loading Environment Variables

The framework automatically loads the `.env` file during bootstrap.

Example:

```php
Core\Env::load(__DIR__ . '/.env');
```

---

### Access Environment Variables

Use the `Env` class anywhere in the framework:

```php
use Core\Env;

Env::get('APP_NAME');
Env::get('APP_DEBUG');
```

Supported automatic value conversion:

| Value | Converted To  |
|-------|---------------|
| true  | boolean true  |
| false | boolean false |
| null  | null          |

---

### Configuration Files

Configuration files are stored inside:

```txt
Config/
```

Example:

```php
<?php

declare(strict_types=1);

use Core\Env;

return [
    'name' => Env::get('APP_NAME', 'DarkDapp'),
    'debug' => Env::get('APP_DEBUG', false),
];
```

---

### Access Configuration Values

The framework supports dot notation:

```php
$config->get('app.name');
$config->get('database.host');
```

Example inside a controller:

```php
use Core\Config;

final class HomeController
{
    public function __construct(
        private readonly Config $config
    ) {}

    public function index(): array
    {
        return [
            'app' => $this->config->get('app.name'),
        ];
    }
}
```

---

### Features

- Lightweight `.env` loader
- Dot notation configuration access
- Automatic dependency injection
- No external libraries required
- PHP 8.2 compatible
- Clean and extensible architecture

---

# Logging System

DarkDapp Framework includes a lightweight built-in logging system.

## Features

- INFO logs
- WARNING logs
- ERROR logs
- DEBUG logs
- Automatic exception logging
- File-based logging
- Thread-safe writes using `LOCK_EX`

## Log File

```txt
storage/logs/app.log
```

## Usage

```php
use Core\Logger;

Logger::info('Application started');

Logger::warning('Invalid request detected');

Logger::error('Database connection failed');

Logger::debug('Debug message');
```

## Example Output

```txt
[2026-08-08 12:30:11] INFO: Application started
[2026-08-08 12:31:02] ERROR: Route not found
```

---

# Exception Handling

DarkDapp Framework provides a centralized exception handling system.

## Features

- HTML exception rendering
- JSON API exception rendering
- Automatic HTTP status handling
- Custom HTTP exceptions
- Automatic exception logging

## Supported Exceptions

| Exception                 | Status |
|---------------------------|--------|
| NotFoundException         | 404    |
| MethodNotAllowedException | 405    |
| HttpException             | Custom |
| Throwable                 | 500    |

## API Error Example

```json
{
  "error": "Route not found",
  "status": 404
}
```

## Debug Mode

When `APP_DEBUG=true`:

- exception message
- file path
- line number

will be displayed automatically.

---

# License

MIT License