<?php

require_once __DIR__ . '/../vendor/autoload.php';

// Load environment variables
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

// Load configurations first
require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../config/database.php';

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
$router->get('/check-email', 'AuthController@checkEmail');
$router->get('/appointments', 'AppointmentController@index');
$router->get('/appointments/create', 'AppointmentController@create');
$router->get('/admin/dashboard', 'AdminController@dashboard');
$router->get('/admin/appointments', 'AdminController@appointments');
$router->get('/users', 'UserController@index');
$router->get('/search', 'SearchController@index');
$router->get('/become-artist', 'SubscriptionController@showUpgradePage');
$router->post('/become-artist/process', 'SubscriptionController@process');

// Artist Routes
$router->get('/artist/profile', 'Artist\ProfileController@show');
$router->get('/artist/profile/edit', 'Artist\ProfileController@edit');         // This is your edit route
$router->post('/artist/profile/update', 'Artist\ProfileController@update');    // This is your update route

$router->get('/artist/posts', 'Artist\PostController@index');
$router->get('/artist/posts/create', 'Artist\PostController@create');
$router->post('/artist/posts', 'Artist\PostController@store');
$router->delete('/artist/posts/{id}', 'Artist\PostController@delete');

// Post routes
$router->get('/artist/posts/{id}/comments', ['App\Controllers\Artist\PostController', 'getComments']);
$router->post('/artist/posts/{id}/comments', ['App\Controllers\Artist\PostController', 'addComment']);
$router->post('/artist/posts/{id}/like', ['App\Controllers\Artist\PostController', 'toggleLike']);
$router->get('/artist/posts/{id}/like-status', ['App\Controllers\Artist\PostController', 'getLikeStatus']);

$router->get('/artist/appointments', ['App\Controllers\Artist\AppointmentController', 'dashboard']);
$router->get('/artist/appointments/slots/{date}', ['App\Controllers\Artist\AppointmentController', 'getTimeSlots']);
$router->get('/artist/appointments/list/{date}', ['App\Controllers\Artist\AppointmentController', 'getAppointments']);
$router->post('/artist/appointments/{id}/status', ['App\Controllers\Artist\AppointmentController', 'updateStatus']);
$router->get('/artist/appointments/{id}/details', ['App\Controllers\Artist\AppointmentController', 'getDetails']);
$router->get('/artist/services', ['App\Controllers\Artist\ServiceController', 'index']);
$router->post('/artist/services/store', ['App\Controllers\Artist\ServiceController', 'store']);
$router->get('/artist/services/{id}', ['App\Controllers\Artist\ServiceController', 'getService']);
$router->post('/artist/services/update', ['App\Controllers\Artist\ServiceController', 'update']);

// Add this with your other artist routes
$router->get('/artist/working-hours', ['App\Controllers\Artist\ProfileController', 'getWorkingHours']);

// Add this with your other routes
$router->get('/artists', ['App\Controllers\ArtistSearchController', 'index']);
$router->get('/artists/profile/{id}', ['App\Controllers\ArtistSearchController', 'profile']);

// Add these new routes for public post interactions
$router->get('/posts/{id}/comments', ['App\Controllers\PostInteractionController', 'getComments']);
$router->post('/posts/{id}/comments', ['App\Controllers\PostInteractionController', 'addComment']);
$router->post('/posts/{id}/like', ['App\Controllers\PostInteractionController', 'toggleLike']);
$router->get('/posts/{id}/like-status', ['App\Controllers\PostInteractionController', 'getLikeStatus']);

$router->get('/payment', 'PaymentController@show');

// Dispatch the request
$router->dispatch();
