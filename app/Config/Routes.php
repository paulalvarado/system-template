<?php

use App\Controllers\Home;
use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/login', [Home::class, 'loginPage']);
$routes->get('/register', [Home::class, 'registerPage']);
