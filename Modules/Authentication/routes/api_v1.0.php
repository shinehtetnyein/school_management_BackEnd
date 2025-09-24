<?php
use Illuminate\Support\Facades\Route;

Route::post("/login",[AuthenticationApiController::class,'login']);
Route::post('/register',[AuthenticationApiController::class,'register']);
