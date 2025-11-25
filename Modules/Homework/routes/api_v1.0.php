<?php

use Illuminate\Support\Facades\Route;
use Modules\Homework\app\Http\Controllers\HomeworkController;
use Modules\Homework\app\Http\Controllers\StudentHomeworkController;

Route::apiResource('homeworks', HomeworkController::class);
Route::apiResource('homework-submissions', StudentHomeworkController::class);
