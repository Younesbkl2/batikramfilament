<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Clear any existing output buffers
while (ob_get_level() > 0) {
    ob_end_clean();
}

// Maintenance mode check
if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// Autoloader
require __DIR__.'/../vendor/autoload.php';

// Bootstrap Laravel
/** @var Application $app */
$app = require_once __DIR__.'/../bootstrap/app.php';

// Handle request
$app->handleRequest(Request::capture());