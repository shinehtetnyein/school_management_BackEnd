<?php

use Illuminate\Support\Facades\Route;
use Modules\TimeTable\app\Http\Controllers\TimeTableApiController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('courses', TimeTableApiController::class);

    // Additional course routes
    Route::get('courses/{id}/students', [TimeTableApiController::class, 'getEnrolledStudents']);
    Route::get('courses/{id}/teachers', [TimeTableApiController::class, 'getAssignedTeachers']);
    Route::post('courses/{id}/enroll', [TimeTableApiController::class, 'enrollStudents']);
    Route::post('courses/{id}/assign-teacher', [TimeTableApiController::class, 'assignTeacher']);
});
