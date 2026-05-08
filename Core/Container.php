<?php

declare(strict_types=1);

namespace Core;

use Closure;
use ReflectionClass;
use ReflectionException;
use RuntimeException;

/**
 * Dependency Injection Container.
 *
 * Responsible for:
 * - service bindings
 * - singleton management
 * - automatic dependency resolution
 * - class instantiation via reflection
 */
final class Container
{
    /**
     * Registered service bindings.
     *
     * @var array<string, Closure>
     */
    private array $bindings = [];

    /**
     * Resolved singleton instances.
     *
     * @var array<string, mixed>
     */
    private array $instances = [];

    /**
     * Register a transient service binding.
     */
    public function bind(string $key, Closure $resolver): void
    {
        $this->bindings[$key] = $resolver;
    }

    /**
     * Register a singleton service.
     */
    public function singleton(string $key, Closure $resolver): void
    {
        $this->bindings[$key] = function (self $container) use ($key, $resolver): mixed {

            if (!array_key_exists($key, $this->instances)) {
                $this->instances[$key] = $resolver($container);
            }

            return $this->instances[$key];
        };
    }

    /**
     * Resolve instance from container.
     *
     * @template T
     * @param class-string<T> $key
     * @return T
     */
    public function get(string $key): mixed
    {
        if (isset($this->bindings[$key])) {
            return ($this->bindings[$key])($this);
        }

        return $this->resolve($key);
    }

    /**
     * Automatically resolve class dependencies.
     */
    private function resolve(string $class): object
    {
        if (!class_exists($class)) {
            throw new RuntimeException("Class not found: $class");
        }

        try {

            $reflection = new ReflectionClass($class);

            if (!$reflection->isInstantiable()) {
                throw new RuntimeException(
                    "Class is not instantiable: $class"
                );
            }

            $constructor = $reflection->getConstructor();

            /*
             |--------------------------------------------------------------------------
             | No Constructor Dependencies
             |--------------------------------------------------------------------------
             */

            if ($constructor === null) {
                return new $class();
            }

            $dependencies = [];

            foreach ($constructor->getParameters() as $param) {

                $type = $param->getType();

                if (!$type || $type->isBuiltin()) {
                    throw new RuntimeException(
                        "Cannot resolve parameter \${$param->getName()} in $class"
                    );
                }

                $dependencies[] = $this->get(
                    $type->getName()
                );
            }

            return $reflection->newInstanceArgs($dependencies);

        } catch (ReflectionException $e) {

            throw new RuntimeException(
                $e->getMessage(),
                0,
                $e
            );
        }
    }
}