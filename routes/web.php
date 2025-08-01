<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

Route::get('/', function () {
    return view('welcome');
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
        $dbConfig = config('database.connections.' . config('database.default'));
        
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
            'database_config' => [
                'driver' => $dbConfig['driver'] ?? 'unknown',
                'host' => $dbConfig['host'] ?? 'unknown',
                'database' => $dbConfig['database'] ?? 'unknown',
            ],
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
