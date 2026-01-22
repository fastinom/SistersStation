<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Test admin dashboard route
$route = \Illuminate\Support\Facades\Route::getRoutes()->getByName('admin.dashboard');
echo "Admin Dashboard Route:\n";
echo "URI: " . $route->uri() . "\n";
echo "Action: " . $route->getActionName() . "\n";
echo "Middleware: " . implode(', ', $route->middleware()) . "\n";
