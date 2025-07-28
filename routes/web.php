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
