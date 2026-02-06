<?php
class Router
{
    private array $routes = [
        'GET' => [],
        'POST' => [],
    ];

    public function get(string $path, array $handler): void
    {
        $this->routes['GET'][$path] = $handler;
    }

    public function post(string $path, array $handler): void
    {
        $this->routes['POST'][$path] = $handler;
    }

    public function dispatch(): void
    {
        $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        $uri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?? '/';
        $path = rtrim($uri, '/');
        if ($path === '') {
            $path = '/';
        }

        $base = $GLOBALS['config']['app']['base_url'] ?? '';
        $basePath = $base;
        if ($basePath !== '' && strpos($basePath, '/index.php') !== false) {
            $basePath = rtrim(dirname($basePath), '/');
        }
        $basePath = rtrim($basePath, '/');

        if ($basePath !== '' && strpos($path, $basePath) === 0) {
            $path = substr($path, strlen($basePath));
            if ($path === '') {
                $path = '/';
            }
        }

        if (strpos($path, '/index.php') === 0) {
            $path = substr($path, strlen('/index.php'));
            if ($path === '') {
                $path = '/';
            }
        }

        $pathInfo = $_SERVER['PATH_INFO'] ?? '';
        if ($pathInfo !== '') {
            $path = '/' . ltrim($pathInfo, '/');
        } elseif (!empty($_GET['s'])) {
            $path = '/' . ltrim((string)$_GET['s'], '/');
        }

        $path = rtrim($path, '/');
        if ($path === '') {
            $path = '/';
        }

        if ($basePath !== '' && strpos($path, $basePath) === 0) {
            $path = substr($path, strlen($basePath));
            if ($path === '') {
                $path = '/';
            }
        }

        if (strpos($path, '/index.php') === 0) {
            $path = substr($path, strlen('/index.php'));
            if ($path === '') {
                $path = '/';
            }
        }

        $handler = $this->routes[$method][$path] ?? null;

        if (!$handler && $path === '/') {
            Auth::redirect('/admin/login');
        }

        if (!$handler) {
            http_response_code(404);
            echo 'Not Found';
            return;
        }

        [$class, $action] = $handler;
        $controller = new $class();
        $controller->$action();
    }
}
