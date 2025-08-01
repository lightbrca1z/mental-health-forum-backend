<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

Route::get('/', function () {
    return view('welcome');
});

// Health check endpoint for Railway
Route::get('/health', function () {
    try {
        // Check database connection
        DB::connection()->getPdo();
        
        return response('healthy', 200)
            ->header('Content-Type', 'text/plain');
    } catch (\Exception $e) {
        return response('unhealthy: ' . $e->getMessage(), 500)
            ->header('Content-Type', 'text/plain');
    }
});

// Alternative health check endpoint
Route::get('/up', function () {
    try {
        // Check database connection
        DB::connection()->getPdo();
        
        return response('ok', 200)
            ->header('Content-Type', 'text/plain');
    } catch (\Exception $e) {
        return response('error: ' . $e->getMessage(), 500)
            ->header('Content-Type', 'text/plain');
    }
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
