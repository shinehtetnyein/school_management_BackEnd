<?php

use Illuminate\Support\Facades\Route;
use Modules\Attendance\Http\Controllers\AttendanceController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('attendances', AttendanceController::class);

    // Additional attendance routes
    Route::get('attendances/student/{studentId}', [AttendanceController::class, 'getStudentAttendance']);
    Route::get('attendances/class/{classId}', [AttendanceController::class, 'getClassAttendance']);
    Route::post('attendances/bulk', [AttendanceController::class, 'bulkStore']);
});
