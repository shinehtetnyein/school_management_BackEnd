<?php

use Illuminate\Support\Facades\Route;
use Modules\Courses\app\Http\Controllers\CourseController;
Route::apiResource('courses', CourseController::class);
