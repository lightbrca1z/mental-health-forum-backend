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
        $appKey = config('app.key');
        
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
            'app_key_exists' => !empty($appKey),
            'app_key_length' => strlen($appKey),
            'database_config' => [
                'driver' => $dbConfig['driver'] ?? 'unknown',
                'host' => $dbConfig['host'] ?? 'unknown',
                'database' => $dbConfig['database'] ?? 'unknown',
            ],
            'database_connected' => $dbConnected,
            'database_error' => $dbError,
            'session_driver' => config('session.driver'),
            'session_connection' => config('session.connection'),
            'session_files_path' => config('session.files'),
            'cache_driver' => config('cache.default'),
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'error' => $e->getMessage(),
            'timestamp' => now(),
        ], 500);
    }
});
