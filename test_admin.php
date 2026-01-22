<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$user = \App\Models\User::where('email', 'admin@babywear.com')->first();

echo "User ID: " . $user->id . "\n";
echo "User Email: " . $user->email . "\n";
echo "User Type: " . $user->user_type . "\n";
echo "isAdmin(): " . ($user->isAdmin() ? 'true' : 'false') . "\n";
echo "isSeller(): " . ($user->isSeller() ? 'true' : 'false') . "\n";
