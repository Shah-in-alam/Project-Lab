<?php

use Core\Router;

$router = new Router();

// Auth Routes
$router->get('/login', 'AuthController@showLogin');
$router->post('/auth/login', 'AuthController@login');
$router->get('/register', 'AuthController@showRegister');
$router->post('/register', 'AuthController@register');
$router->get('/auth/logout', 'AuthController@logout');

// Home Route
$router->get('/', 'HomeController@index');

return $router; 