<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

// Route untuk menjalankan WebSocket server (CLI)
$routes->cli('Websocket/start', 'Websocket::start');

// // Route opsional (HTTP) untuk testing dari browser
// $routes->get('websocket/test', 'Websocket::test');

$routes->get('websocket/user/(:any)', 'Websocket::user/$1');

$routes->get('chart', 'Chart::index');
$routes->get('chart/db', 'Chart::db');

// $routes->setAutoRoute(true);
