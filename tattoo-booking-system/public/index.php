<?php

// Load configurations first
require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../vendor/autoload.php';

use Core\Router;

// Initialize the router
$router = new Router();

// Define routes
$router->get('/', 'HomeController@index');
$router->get('/register', 'AuthController@register');
$router->get('/login', 'AuthController@login');
$router->post('/register', 'AuthController@store');
$router->post('/login', 'AuthController@authenticate');
$router->post('/logout', 'AuthController@logout');
$router->get('/verify-email', 'AuthController@verifyEmail');
$router->get('/appointments', 'AppointmentController@index');
$router->get('/appointments/create', 'AppointmentController@create');
$router->get('/admin/dashboard', 'AdminController@dashboard');
$router->get('/admin/appointments', 'AdminController@appointments');

// Dispatch the request
$router->dispatch();
