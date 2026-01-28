<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use App\Kernel\Router;
use App\Kernel\Container;

// Load Environment
$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->safeLoad();

// Detect Base Path
$scriptName = $_SERVER['SCRIPT_NAME'];
$basePath = str_replace('/public/index.php', '', $scriptName);
$basePath = rtrim($basePath, '/') . '/';
define('BASE_PATH', $basePath);

// Initialize DI Container
$container = new Container();

// Initialize Router
$router = new Router($container);
$router->setBasePath($basePath);

// Defined Routes
require_once __DIR__ . '/../routes/web.php';
require_once __DIR__ . '/../routes/api.php';

// Dispatch
$router->dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
