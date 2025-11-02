<?php

use Illuminate\Support\Facades\Route;
use Modules\Departments\app\Http\Controllers\DepartmentController;

Route::middleware(['auth:sanctum'])->group(function () {
    // Basic CRUD operations
    Route::get('/departments', [DepartmentController::class, 'index']);
    Route::get('/departments/{id}', [DepartmentController::class, 'show']);
    Route::post('/departments', [DepartmentController::class, 'store']);
    Route::put('/departments/{id}', [DepartmentController::class, 'update']);
    Route::delete('/departments/{id}', [DepartmentController::class, 'destroy']);

    // Department Events
    Route::get('/departments/{id}/events', [DepartmentController::class, 'events']);
    Route::post('/departments/{id}/events', [DepartmentController::class, 'createEvent']);

    // Department Announcements
    Route::get('/departments/{id}/announcements', [DepartmentController::class, 'announcements']);
    Route::post('/departments/{id}/announcements', [DepartmentController::class, 'createAnnouncement']);

    // Department Schedules
    Route::get('/departments/{id}/schedules', [DepartmentController::class, 'schedules']);
    Route::post('/departments/{id}/schedules', [DepartmentController::class, 'createSchedule']);

    // Department Statistics and Reports
    Route::get('/departments/{id}/statistics', [DepartmentController::class, 'statistics']);
    Route::get('/departments/{id}/teacher-workload', [DepartmentController::class, 'teacherWorkload']);
    Route::get('/departments/{id}/resource-utilization', [DepartmentController::class, 'resourceUtilization']);

    // Academic Calendar
    Route::get('/departments/{id}/academic-calendar', [DepartmentController::class, 'academicCalendar']);

    // Performance Metrics
    Route::get('/departments/{id}/performance-metrics', [DepartmentController::class, 'performanceMetrics']);
});
