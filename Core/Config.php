<?php

declare(strict_types=1);

namespace Core;

use RuntimeException;

final class Config
{
    /**
     * Loaded configuration files
     *
     * @var array<string, array<string, mixed>>
     */
    private array $items = [];

    public function __construct(
        private readonly string $path = __DIR__ . '/../Config'
    ) {
        $this->load();
    }

    /**
     * Load all config files
     */
    private function load(): void
    {
        $files = glob($this->path . '/*.php');

        if ($files === false) {
            return;
        }

        foreach ($files as $file) {

            $key = pathinfo($file, PATHINFO_FILENAME);

            $config = require $file;

            if (!is_array($config)) {
                throw new RuntimeException(
                    "Invalid config file: $file"
                );
            }

            $this->items[$key] = $config;
        }
    }

    /**
     * Get config value using dot notation
     */
    public function get(
        string $key,
        mixed $default = null
    ): mixed {

        $segments = explode('.', $key);

        $data = $this->items;

        foreach ($segments as $segment) {

            if (!is_array($data) || !array_key_exists($segment, $data)) {
                return $default;
            }

            $data = $data[$segment];
        }

        return $data;
    }
}