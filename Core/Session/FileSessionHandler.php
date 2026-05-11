<?php

declare(strict_types=1);

namespace Core\Session;

use RuntimeException;
use SessionHandlerInterface;

/**
 * Secure file-based session storage.
 */
final readonly class FileSessionHandler implements SessionHandlerInterface
{
    public function __construct(
        private string $path,
        private string $prefix,
    ) {}

    public function open(
        string $path,
        string $name
    ): bool {

        if (!is_dir($this->path)) {
            mkdir($this->path, 0755, true);
        }

        return true;
    }

    public function close(): bool
    {
        return true;
    }

    public function read(string $id): string|false
    {
        $file = $this->file($id);

        if (!is_file($file)) {
            return '';
        }

        return (string) file_get_contents($file);
    }

    public function write(
        string $id,
        string $data
    ): bool {

        return file_put_contents(
                $this->file($id),
                $data,
                LOCK_EX
            ) !== false;
    }

    public function destroy(string $id): bool
    {
        $file = $this->file($id);

        if (is_file($file)) {
            @unlink($file);
        }

        return true;
    }

    public function gc(int $max_lifetime): int|false
    {
        $files = glob(
            $this->path . '/' . $this->prefix . '_*'
        );

        if ($files === false) {
            return false;
        }

        $deleted = 0;

        foreach ($files as $file) {

            if (
                filemtime($file) + $max_lifetime
                < time()
            ) {

                unlink($file);

                $deleted++;
            }
        }

        return $deleted;
    }

    /**
     * Build safe session file path.
     */
    private function file(string $id): string
    {
        $id = preg_replace('/[^a-zA-Z0-9]/', '', $id);

        if ($id === '') {
            throw new RuntimeException(
                'Invalid session id.'
            );
        }

        return sprintf(
            '%s/%s_%s',
            rtrim($this->path, '/'),
            $this->prefix,
            $id
        );
    }
}