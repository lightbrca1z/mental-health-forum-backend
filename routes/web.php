<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Health check endpoint for Railway
Route::get('/health', function () {
    return response('healthy', 200)
        ->header('Content-Type', 'text/plain');
});

// Debug endpoint
Route::get('/debug', function () {
    return response()->json([
        'status' => 'ok',
        'timestamp' => now(),
        'database' => config('database.default'),
        'app_env' => config('app.env'),
        'app_debug' => config('app.debug'),
        'database_path' => config('database.connections.sqlite.database'),
        'database_exists' => file_exists(config('database.connections.sqlite.database')),
    ]);
});
