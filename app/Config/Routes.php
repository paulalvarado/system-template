<?php

use App\Controllers\AuthController;
use App\Controllers\Home;
use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('login', [Home::class, 'loginPage']);
$routes->get('register', [Home::class, 'registerPage']);

// API group
$routes->group('api', function (RouteCollection $routes) {
    $routes->post('login', [AuthController::class, 'login']);
    $routes->post('register', [AuthController::class, 'register']);
    $routes->post('logout', [AuthController::class, 'logout']);
});
