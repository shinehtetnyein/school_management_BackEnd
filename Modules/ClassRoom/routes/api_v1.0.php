<?php

use Illuminate\Support\Facades\Route;
use Modules\ClassRoom\app\Http\Controllers\ClassRoomApiController;
use Modules\ClassRoom\app\Http\Controllers\SectionController;

Route::apiResource('classrooms', ClassRoomApiController::class);
Route::apiResource('sections', SectionController::class);

