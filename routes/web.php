<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

Route::get('/', function () {
    return view('welcome');
});

// Simple health check endpoint for Railway (no database check)
Route::get('/health', function () {
    return response('healthy', 200)
        ->header('Content-Type', 'text/plain');
});

// Alternative health check endpoint (no database check)
Route::get('/up', function () {
    return response('ok', 200)
        ->header('Content-Type', 'text/plain');
});

// Detailed health check endpoint with database check
Route::get('/health/detailed', function () {
    try {
        // Check database connection
        DB::connection()->getPdo();
        
        return response()->json([
            'status' => 'healthy',
            'database' => 'connected',
            'timestamp' => now(),
        ]);
    } catch (\Exception $e) {
        Log::error('Health check failed: ' . $e->getMessage());
        
        return response()->json([
            'status' => 'unhealthy',
            'database' => 'disconnected',
            'error' => $e->getMessage(),
            'timestamp' => now(),
        ], 500);
    }
});

// Debug endpoint
Route::get('/debug', function () {
    try {
        $dbConnected = false;
        $dbError = null;
        
        try {
            DB::connection()->getPdo();
            $dbConnected = true;
        } catch (\Exception $e) {
            $dbError = $e->getMessage();
        }
        
        return response()->json([
            'status' => 'ok',
            'timestamp' => now(),
            'database' => config('database.default'),
            'app_env' => config('app.env'),
            'app_debug' => config('app.debug'),
            'database_path' => config('database.connections.sqlite.database'),
            'database_exists' => file_exists(config('database.connections.sqlite.database')),
            'database_connected' => $dbConnected,
            'database_error' => $dbError,
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'error' => $e->getMessage(),
            'timestamp' => now(),
        ], 500);
    }
});
