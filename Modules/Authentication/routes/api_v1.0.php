<?php
use Illuminate\Support\Facades\Route;
use Modules\Authentication\App\Http\Controllers\AuthenticationApiController;
use Modules\Authentication\App\Http\Controllers\GoogleAuthController;

Route::post('/register', [AuthenticationApiController::class, 'register']);
Route::post('/login', [AuthenticationApiController::class, 'login']);
Route::post('/logout', [AuthenticationApiController::class, 'logout'])->middleware('auth:sanctum');

// Google OAuth routes
Route::get('/google', [GoogleAuthController::class, 'redirect'])->name('google.redirect');
Route::get('/google/callback', [GoogleAuthController::class, 'callback'])->name('google.callback');
