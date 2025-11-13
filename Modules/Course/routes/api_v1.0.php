<?php
// Modules/Course/routes/api.php

use Illuminate\Support\Facades\Route;
use Modules\Course\App\Http\Controllers\CourseController;
use Modules\Course\App\Http\Controllers\EnrollmentController;

Route::apiResource('courses', CourseController::class);

// Additional Course Routes
Route::prefix('courses/{course}')->group(function () {
    Route::get('subjects', [CourseController::class, 'getCourseSubjects']);
    Route::post('assign-subjects', [CourseController::class, 'assignSubjects']);
});

// Enrollment Routes
Route::apiResource('enrollments', EnrollmentController::class);
