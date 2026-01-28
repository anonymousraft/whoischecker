<?php

/** @var App\Kernel\Router $router */

$router->post('/api/lookup', [App\Controllers\ApiController::class, 'lookup']);
$router->post('/api/bulk/process', [App\Controllers\ApiController::class, 'bulkProcess']);
$router->post('/api/dns/query', [App\Controllers\ApiController::class, 'dnsQuery']);
