<?php

namespace Core;

class Controller
{
    protected function view($name, $data = [])
    {
        extract($data);
        require "../app/views/{$name}.php";
    }

    protected function redirect($url)
    {
        header("Location: {$url}");
        exit();
    }
}
