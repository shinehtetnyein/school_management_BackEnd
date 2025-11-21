<?php

use Illuminate\Support\Facades\Route;
use Modules\TimeTable\app\Http\Controllers\TimeTableApiController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    // CRUD operations for timetables
    Route::apiResource('timetables', TimeTableApiController::class);

    // Additional timetable routes
    Route::get('timetables/classroom-day', [TimeTableApiController::class, 'getByClassroomAndDay']);
    Route::get('timetables/teacher-schedule', [TimeTableApiController::class, 'getByTeacher']);
});

