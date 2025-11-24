<?php

use Illuminate\Support\Facades\Route;
use Modules\Attendance\app\Http\Controllers\AttendanceApiController;

use App\Console\Enums\Role;

Route::prefix('v1')->group(function () {
    Route::middleware(['auth:sanctum'])->group(function () {
        // Read access for authenticated users
        Route::get('attendances', [AttendanceApiController::class, 'index']);
        Route::get('attendances/{id}', [AttendanceApiController::class, 'show']);
        Route::get('attendances/student/{studentId}', [AttendanceApiController::class, 'getStudentAttendance']);
        Route::get('attendances/class/{classId}', [AttendanceApiController::class, 'getClassAttendance']);

        // Bulk store and other write ops restricted to staff (teacher/admin)
        Route::post('attendances/bulk', [AttendanceApiController::class, 'bulkStore'])->middleware('role:' . Role::TEACHER->label() . '|' . Role::ADMIN->label() . '|' . Role::ROOT_ADMIN->label());
    });

    Route::middleware(['auth:sanctum', 'role:' . Role::TEACHER->label() . '|' . Role::ADMIN->label() . '|' . Role::ROOT_ADMIN->label()])->group(function () {
        Route::post('attendances', [AttendanceApiController::class, 'store']);
        Route::put('attendances/{id}', [AttendanceApiController::class, 'update']);
        Route::delete('attendances/{id}', [AttendanceApiController::class, 'destroy']);
    });
});
