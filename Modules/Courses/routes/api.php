<?php

use Illuminate\Support\Facades\Route;
use Modules\Courses\Http\Controllers\CourseController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('courses', CourseController::class);

    // Additional course routes
    Route::get('courses/{id}/students', [CourseController::class, 'getEnrolledStudents']);
    Route::get('courses/{id}/teachers', [CourseController::class, 'getAssignedTeachers']);
    Route::post('courses/{id}/enroll', [CourseController::class, 'enrollStudents']);
    Route::post('courses/{id}/assign-teacher', [CourseController::class, 'assignTeacher']);
});
