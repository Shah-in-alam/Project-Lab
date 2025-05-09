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
        try {
            $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
            $method = $_SERVER['REQUEST_METHOD'];

            if (array_key_exists($uri, $this->routes[$method] ?? [])) {
                $controller = $this->routes[$method][$uri];
                return $this->callController($controller);
            }

            // If no route matches, show 404
            header("HTTP/1.0 404 Not Found");
            $viewPath = __DIR__ . '/../app/views/404.php';
            if (file_exists($viewPath)) {
                require $viewPath;
            } else {
                echo "404 - Page Not Found";
            }
        } catch (\Exception $e) {
            // Log the error
            error_log($e->getMessage());
            
            // Show error page
            header("HTTP/1.0 500 Internal Server Error");
            echo "An error occurred. Please try again later.";
        }
    }

    protected function callController($controller)
    {
        list($controller, $method) = explode('@', $controller);
        $controllerClass = "App\\Controllers\\{$controller}";

        if (class_exists($controllerClass)) {
            $controllerInstance = new $controllerClass();
            if (method_exists($controllerInstance, $method)) {
                return $controllerInstance->$method();
            }
            throw new \Exception("Method {$method} not found in controller {$controller}");
        }

        throw new \Exception("Controller not found: {$controller}");
    }
}
