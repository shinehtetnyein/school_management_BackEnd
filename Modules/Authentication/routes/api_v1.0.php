<?php
use Illuminate\Support\Facades\Route;
use Modules\Authentication\App\Http\Controllers\AuthenticationApiController;

Route::post('/register', [AuthenticationApiController::class, 'register']);
Route::post('/login', [AuthenticationApiController::class, 'login']);
Route::post('/logout', [AuthenticationApiController::class, 'logout'])->middleware('auth:sanctum');
