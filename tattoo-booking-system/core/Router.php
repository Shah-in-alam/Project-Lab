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

        if (array_key_exists($uri, $this->routes[$method] ?? [])) {
            $controller = $this->routes[$method][$uri];
            return $this->callController($controller);
        }

        header("HTTP/1.0 404 Not Found");
        require '../app/views/404.php';
    }

    protected function callController($controller)
    {
        list($controller, $method) = explode('@', $controller);
        $controllerClass = "App\\Controllers\\{$controller}";

        if (class_exists($controllerClass)) {
            $controllerInstance = new $controllerClass();
            return $controllerInstance->$method();
        }

        throw new \Exception("Controller not found: {$controller}");
    }
}
