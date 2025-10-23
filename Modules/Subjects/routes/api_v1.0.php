<?php

use Illuminate\Support\Facades\Route;
use Modules\Subjects\app\Http\Controllers\SubjectApiController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('subjects', SubjectApiController::class);

    // Additional subject routes
    Route::get('subjects/{id}/teachers', [SubjectApiController::class, 'getTeachers']);
    Route::get('subjects/{id}/students', [SubjectApiController::class, 'getStudents']);
    Route::post('subjects/{id}/assign-teacher', [SubjectApiController::class, 'assignTeacher']);
    Route::get('subjects/{id}/materials', [SubjectApiController::class, 'getMaterials']);
    Route::post('subjects/{id}/materials', [SubjectApiController::class, 'uploadMaterial']);
});
