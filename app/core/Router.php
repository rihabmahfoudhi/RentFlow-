<?php

declare(strict_types=1);

namespace App\Core;

final class Router
{
    /** @var array<string, callable> */
    private array $getRoutes = [];

    /** @var array<string, callable> */
    private array $postRoutes = [];

    private $notFoundHandler;

    public function get(string $route, callable $handler): void
    {
        $this->getRoutes[$this->normalize($route)] = $handler;
    }

    public function post(string $route, callable $handler): void
    {
        $this->postRoutes[$this->normalize($route)] = $handler;
    }

    public function setNotFound(callable $handler): void
    {
        $this->notFoundHandler = $handler;
    }

    public function dispatch(string $route, string $method): void
    {
        $normalizedRoute = $this->normalize($route);
        $method = strtoupper($method);

        $handler = null;

        if ($method === 'GET') {
            $handler = $this->getRoutes[$normalizedRoute] ?? null;
        }

        if ($method === 'POST') {
            $handler = $this->postRoutes[$normalizedRoute] ?? null;
        }

        if (is_callable($handler)) {
            $handler();
            return;
        }

        http_response_code(404);

        if (is_callable($this->notFoundHandler)) {
            ($this->notFoundHandler)();
            return;
        }

        echo '404 - Page not found';
    }

    private function normalize(string $route): string
    {
        return trim($route, '/');
    }
}
