<?php

use Illuminate\Support\Facades\Route;
use Modules\Attendance\app\Http\Controllers\AttendanceApiController;

    Route::apiResource('attendances', AttendanceApiController::class);

    // Additional attendance routes
    Route::get('attendances/student/{studentId}', [AttendanceApiController::class, 'getStudentAttendance']);
    Route::get('attendances/class/{classId}', [AttendanceApiController::class, 'getClassAttendance']);
    Route::post('attendances/bulk', [AttendanceApiController::class, 'bulkStore']);
