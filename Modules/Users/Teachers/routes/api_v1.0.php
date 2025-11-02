<?php

use Illuminate\Support\Facades\Route;
use Modules\Users\Teacher\app\Http\Controllers\TeacherController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('teachers', TeacherController::class)->names('teacher');
});

