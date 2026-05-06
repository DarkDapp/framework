<?php

declare(strict_types=1);

namespace Core;

use Core\Exceptions\Handler;
use Throwable;

/**
 * Main application kernel.
 *
 * Responsible for:
 * - bootstrapping service providers
 * - resolving core services
 * - handling the request lifecycle
 * - dispatching routes
 * - sending responses
 */
final readonly class Application
{
    private Request $request;
    private Response $response;
    private Router $router;
    private Handler $exceptionHandler;

    /**
     * @param array<int, class-string> $providers
     */
    public function __construct(
        private array $providers = [],
        private Container $container = new Container(),
    ) {
        $this->registerProviders();

        $this->request = $this->container->get(Request::class);
        $this->response = $this->container->get(Response::class);
        $this->router = $this->container->get(Router::class);
        $this->exceptionHandler = $this->container->get(Handler::class);
    }

    /**
     * Register application service providers.
     */
    private function registerProviders(): void
    {
        foreach ($this->providers as $provider) {
            (new $provider($this->container))->register();
        }
    }

    /**
     * Start the HTTP request lifecycle.
     */
    public function run(): void
    {
        try {

            $result = $this->router->dispatch(
                $this->request->path(),
                $this->request->method()
            );

            $this->handleResponse($result);

        } catch (Throwable $e) {

            $this->exceptionHandler->handle($e);
        }
    }

    /**
     * Send the final response to the client.
     */
    private function handleResponse(mixed $result): void
    {
        if ($result === null) {
            return;
        }

        $this->response->send($result);
    }
}