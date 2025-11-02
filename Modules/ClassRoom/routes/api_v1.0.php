<?php

use Illuminate\Support\Facades\Route;
use Modules\ClassRoom\app\Http\Controllers\ClassRoomApiController;

Route::apiResource('classrooms', ClassRoomApiController::class);

