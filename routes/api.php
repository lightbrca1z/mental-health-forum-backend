<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

// CORS プリフライトリクエストを処理
Route::options('{any}', function () {
    return response('', 200)
        ->header('Access-Control-Allow-Origin', '*')
        ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
        ->header('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-Requested-With');
})->where('any', '.*');

// API ルート
Route::middleware(['api', 'cors'])->group(function () {
    // Posts routes
    Route::get('/posts', [PostController::class, 'index']);
    Route::post('/posts', [PostController::class, 'store']);
    Route::get('/posts/{post}', [PostController::class, 'show']);
    Route::put('/posts/{post}', [PostController::class, 'update']);
    Route::delete('/posts/{post}', [PostController::class, 'destroy']);

    // Comments routes
    Route::post('/posts/{post}/comments', [CommentController::class, 'store']);
    Route::put('/comments/{comment}', [CommentController::class, 'update']);
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy']);
});

// デバッグ用エンドポイント
Route::get('/debug', function () {
    $debugInfo = [
        'status' => 'ok',
        'timestamp' => now(),
        'database' => config('database.default'),
        'app_env' => config('app.env'),
        'app_debug' => config('app.debug'),
        'app_url' => config('app.url'),
        'cors_allowed_origins' => config('cors.allowed_origins'),
        'cors_allowed_methods' => config('cors.allowed_methods'),
        'cors_allowed_headers' => config('cors.allowed_headers'),
        'request_origin' => request()->header('Origin'),
        'request_method' => request()->method(),
        'request_headers' => request()->headers->all(),
    ];

    // データベース接続テスト
    try {
        $pdo = DB::connection()->getPdo();
        $debugInfo['database_connection'] = 'success';
        $debugInfo['database_name'] = $pdo->query('SELECT DATABASE()')->fetchColumn();
        
        // テーブル一覧を取得
        $tables = [];
        if (Schema::hasTable('posts')) {
            $tables['posts'] = Schema::getColumnListing('posts');
        }
        if (Schema::hasTable('comments')) {
            $tables['comments'] = Schema::getColumnListing('comments');
        }
        $debugInfo['tables'] = $tables;
        
        // 投稿数を取得
        try {
            $postCount = DB::table('posts')->count();
            $debugInfo['post_count'] = $postCount;
        } catch (\Exception $e) {
            $debugInfo['post_count'] = 'error: ' . $e->getMessage();
        }
        
    } catch (\Exception $e) {
        $debugInfo['database_connection'] = 'failed';
        $debugInfo['database_error'] = $e->getMessage();
    }

    return response()->json($debugInfo);
}); 