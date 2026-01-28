<?php

/** @var App\Kernel\Router $router */

$router->get('/', [App\Controllers\DashboardController::class, 'index']);
$router->get('/bulk', [App\Controllers\DashboardController::class, 'bulk']);
$router->get('/dns', [App\Controllers\DashboardController::class, 'dns']);

