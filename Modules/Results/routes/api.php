<?php

use Illuminate\Support\Facades\Route;
use Modules\Results\app\Http\Controllers\ResultController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('results', ResultController::class);

    // Additional result routes
    Route::get('results/student/{studentId}', [ResultController::class, 'getStudentResults']);
    Route::get('results/class/{classId}', [ResultController::class, 'getClassResults']);
    Route::get('results/subject/{subjectId}', [ResultController::class, 'getSubjectResults']);
    Route::post('results/publish', [ResultController::class, 'publishResults']);
    Route::get('results/analytics', [ResultController::class, 'getAnalytics']);
});
