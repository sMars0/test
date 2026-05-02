<?php

declare(strict_types=1);

namespace App\Core;

final class Request
{
    /**
     * @param array<string, string> $query
     */
    public function __construct(
        private readonly string $method,
        private readonly string $path,
        private readonly array $query
    ) {
    }

    public static function fromGlobals(): self
    {
        $path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';

        return new self($_SERVER['REQUEST_METHOD'] ?? 'GET', rtrim($path, '/') ?: '/', $_GET);
    }

    public function method(): string
    {
        return $this->method;
    }

    public function path(): string
    {
        return $this->path;
    }

    public function query(string $key, mixed $default = null): mixed
    {
        return $this->query[$key] ?? $default;
    }
}
