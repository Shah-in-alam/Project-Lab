<?php

namespace Core;

class Controller
{
    protected function view($name, $data = [])
    {
        extract($data);
        $viewPath = __DIR__ . "/../app/views/{$name}.php";
        if (file_exists($viewPath)) {
            require $viewPath;
        } else {
            throw new \Exception("View {$name} not found");
        }
    }

    protected function redirect($url)
    {
        header("Location: {$url}");
        exit();
    }
}
