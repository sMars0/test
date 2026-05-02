<?php

declare(strict_types=1);

namespace App\Core;

final class Router
{
    /** @var array<int, array{method:string,pattern:string,handler:callable}> */
    private array $routes = [];

    public function get(string $pattern, callable $handler): void
    {
        $this->routes[] = [
            'method' => 'GET',
            'pattern' => $pattern,
            'handler' => $handler,
        ];
    }

    /**
     * @return array{status:int,body:string}
     */
    public function dispatch(Request $request): array
    {
        foreach ($this->routes as $route) {
            if ($route['method'] !== $request->method()) {
                continue;
            }

            $params = $this->match($route['pattern'], $request->path());
            if ($params === null) {
                continue;
            }

            $result = call_user_func($route['handler'], $request, ...$params);

            if (is_array($result) && isset($result['status'], $result['body'])) {
                return [
                    'status' => (int) $result['status'],
                    'body' => (string) $result['body'],
                ];
            }

            return [
                'status' => 200,
                'body' => (string) $result,
            ];
        }

        return [
            'status' => 404,
            'body' => '<h1>404 Not Found</h1>',
        ];
    }

    /**
     * @return list<string>|null
     */
    private function match(string $pattern, string $path): ?array
    {
        $regex = preg_replace('#\{[a-zA-Z_]+\}#', '([0-9]+)', $pattern);
        if ($regex === null) {
            return null;
        }

        if (!preg_match('#^' . $regex . '$#', $path, $matches)) {
            return null;
        }

        array_shift($matches);

        return $matches;
    }
}
