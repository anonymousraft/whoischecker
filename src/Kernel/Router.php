<?php

declare(strict_types=1);

namespace App\Kernel;

class Router
{
    private array $routes = [];
    private $container;
    private string $basePath = '/';

    public function __construct($container)
    {
        $this->container = $container;
    }

    public function setBasePath(string $path): void
    {
        $this->basePath = $path;
    }

    public function get(string $path, $handler): void
    {
        $this->addRoute('GET', $path, $handler);
    }

    public function post(string $path, $handler): void
    {
        $this->addRoute('POST', $path, $handler);
    }

    private function addRoute(string $method, string $path, $handler): void
    {
        $this->routes[] = [
            'method' => $method,
            'path' => $path,
            'handler' => $handler
        ];
    }

    public function dispatch(string $uri, string $method): void
    {
        $uri = parse_url($uri, PHP_URL_PATH);

        // Strip base path from URI
        if ($this->basePath !== '/' && str_starts_with($uri, $this->basePath)) {
            $uri = '/' . substr($uri, strlen($this->basePath));
            $uri = str_replace(['//', '///'], '/', $uri);
        }

        foreach ($this->routes as $route) {
            if ($route['method'] === $method && $route['path'] === $uri) {
                $this->executeHandler($route['handler']);
                return;
            }
        }

        header("HTTP/1.0 404 Not Found");
        echo "404 - Not Found";
    }

    private function executeHandler($handler): void
    {
        if (is_callable($handler)) {
            $handler();
            return;
        }

        if (is_array($handler)) {
            [$controllerClass, $method] = $handler;
            $controller = new $controllerClass($this->container);
            $controller->$method();
        }
    }
}
