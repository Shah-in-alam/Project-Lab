<?php

namespace Core;

class Router
{
    protected $routes = [];

    public function get($uri, $controller)
    {
        $this->routes['GET'][$uri] = $controller;
    }

    public function post($uri, $controller)
    {
        $this->routes['POST'][$uri] = $controller;
    }

    public function dispatch()
    {
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $method = $_SERVER['REQUEST_METHOD'];

        // Check if route exists
        if (isset($this->routes[$method][$uri])) {
            return $this->callController($this->routes[$method][$uri]);
        }

        // Handle 404
        if (file_exists(__DIR__ . '/../app/views/404.php')) {
            require __DIR__ . '/../app/views/404.php';
        } else {
            echo "404 - Page Not Found";
        }
    }

    protected function callController($controller)
    {
        // Split controller and method
        list($controller, $method) = explode('@', $controller);

        // Add namespace
        $controller = "App\\Controllers\\{$controller}";

        if (class_exists($controller)) {
            $controllerInstance = new $controller();

            if (method_exists($controllerInstance, $method)) {
                return $controllerInstance->$method();
            }
        }

        // If controller or method doesn't exist, show 404
        if (file_exists(__DIR__ . '/../app/views/404.php')) {
            require __DIR__ . '/../app/views/404.php';
        } else {
            echo "404 - Page Not Found";
        }
    }
}
