<?php

namespace Core;

class Router
{
    protected $routes = [
        'GET' => [],
        'POST' => [],
        'PUT' => [],
        'DELETE' => []
    ];

    public function get($uri, $controller)
    {
        $this->routes['GET'][$uri] = $controller;
    }

    public function post($uri, $controller)
    {
        $this->routes['POST'][$uri] = $controller;
    }

    public function put($uri, $controller)
    {
        $this->routes['PUT'][$uri] = $controller;
    }

    public function delete($uri, $controller)
    {
        $this->routes['DELETE'][$uri] = $controller;
    }

    public function dispatch()
    {
        $uri = $this->getUri();
        $method = $_SERVER['REQUEST_METHOD'];

        // Check for matching route with dynamic parameters
        foreach ($this->routes[$method] as $route => $handler) {
            $pattern = $this->convertRouteToRegex($route);
            if (preg_match($pattern, $uri, $matches)) {
                array_shift($matches); // Remove the full match

                // Get controller and action
                if (is_array($handler)) {
                    $controller = new $handler[0]();
                    $action = $handler[1];
                } else {
                    list($controller, $action) = explode('@', $handler);
                    $controller = "App\\Controllers\\$controller";
                    $controller = new $controller();
                }

                // Call the action with matched parameters
                return call_user_func_array([$controller, $action], $matches);
            }
        }

        throw new \Exception('No route defined for this URI.');
    }

    private function convertRouteToRegex($route)
    {
        // Convert route parameters to regex pattern
        $pattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '([^/]+)', $route);
        return '@^' . $pattern . '$@D';
    }

    protected function getUri()
    {
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        return $uri === '' ? '/' : $uri;
    }

    protected function getMethod()
    {
        $method = $_POST['_method'] ?? $_SERVER['REQUEST_METHOD'];
        return strtoupper($method);
    }

    protected function callAction($controller, $action)
    {
        $controller = "App\\Controllers\\{$controller}";
        $controller = new $controller();

        if (!method_exists($controller, $action)) {
            throw new \Exception(
                "{$controller} does not respond to the {$action} action."
            );
        }

        return $controller->$action();
    }
}
