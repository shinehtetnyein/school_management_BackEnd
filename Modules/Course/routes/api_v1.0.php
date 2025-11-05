<?php
// Modules/Course/routes/api.php

use Illuminate\Support\Facades\Route;
use Modules\Course\App\Http\Controllers\CourseController;
use Modules\Course\App\Http\Controllers\EnrollmentController;

Route::prefix('courses')->group(function () {
    // Course CRUD Routes
    Route::apiResource('/', CourseController::class)->parameters(['' => 'course']);

    // Additional Course Routes
    Route::prefix('{course}')->group(function () {
        Route::get('subjects', [CourseController::class, 'getCourseSubjects']);
        Route::post('assign-subjects', [CourseController::class, 'assignSubjects']);
    });

    // Enrollment Routes
    Route::prefix('enrollments')->group(function () {
        Route::get('/', [EnrollmentController::class, 'index']);
        Route::post('/', [EnrollmentController::class, 'store']);
        Route::get('user/{userId}', [EnrollmentController::class, 'getUserEnrollments']);
        Route::get('course/{courseId}', [EnrollmentController::class, 'getCourseEnrollments']);
        Route::get('{id}', [EnrollmentController::class, 'show']);
        Route::put('{id}', [EnrollmentController::class, 'update']);
        Route::patch('{id}/status', [EnrollmentController::class, 'updateStatus']);
        Route::delete('{id}', [EnrollmentController::class, 'destroy']);
    });
});
