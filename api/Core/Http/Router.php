<?php

namespace App\Core\Http;

class Router
{
    private array $routes = [];

    public function add(string $method, string $uri, callable $callback): void
    {
        $this->routes[$method][$uri] = $callback;
    }

    public function dispatch(string $method, string $uri): void
    {
        $method = strtoupper($method);
        $uri = parse_url($uri, PHP_URL_PATH); // Remove query strings

        if (isset($this->routes[$method][$uri])) {
            call_user_func($this->routes[$method][$uri]);
        } else {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Route not defined or method not allowed']);
        }
    }
}
