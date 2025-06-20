<?php

use Core\Router;

$router = new Router();

// Auth Routes
$router->get('/login', 'AuthController@showLogin');
$router->post('/auth/login', 'AuthController@login');
$router->get('/register', 'AuthController@showRegister');
$router->post('/register', 'AuthController@store');
$router->get('/auth/logout', 'AuthController@logout');
$router->get('/create-payment-intent', 'PaymentController@createPaymentIntent');// Payment Routes

// Home Route
$router->get('/', 'HomeController@index');

return $router; 