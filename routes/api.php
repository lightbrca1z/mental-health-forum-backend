<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;

// エラーハンドリング用のミドルウェア
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
    return response()->json([
        'status' => 'ok',
        'timestamp' => now(),
        'database' => config('database.default'),
        'app_env' => config('app.env'),
        'app_debug' => config('app.debug'),
    ]);
}); 