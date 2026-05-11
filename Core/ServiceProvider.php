<?php

declare(strict_types=1);

namespace Core;

/**
 * Base service provider.
 *
 * Service providers are responsible for
 * registering services into the container.
 */
abstract class ServiceProvider
{
    public function __construct(
        protected readonly Container $container
    ) {}

    /**
     * Register services
     */
    abstract public function register(): void;
}
