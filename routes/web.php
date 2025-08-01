<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

Route::get('/', function () {
    return view('welcome');
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
