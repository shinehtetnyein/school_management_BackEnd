<?php

use Illuminate\Support\Facades\Route;
use Modules\Attendance\app\Http\Controllers\AttendanceApiController;

    Route::apiResource('attendances', AttendanceApiController::class);
