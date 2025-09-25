<?php
use Illuminate\Support\Facades\Route;

Route::post('/register',[AuthenticationApiController::class,'register']);
Route::get('/login', [AuthenticationApiController::class, 'login'])->middleware('auth:sanctum');