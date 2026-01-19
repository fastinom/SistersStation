<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::get('/__info', function () {
    return response()->json([
        'ok' => true,
        'app' => 'babywear',
        'base_path' => base_path(),
        'config_path' => config_path(),
        'storage_path' => storage_path(),
        'app_url' => config('app.url'),
        'session_driver' => config('session.driver'),
        'session_config_is_array' => is_array(config('session')),
    ]);
});

Route::get('/__ping', function () {
    return response()->json(['ok' => true, 'app' => 'babywear']);
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
