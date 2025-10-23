<?php

use Illuminate\Support\Facades\Route;
use Modules\Result\Http\Controllers\ResultApiController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('results', ResultApiController::class);

    // Additional result routes
    Route::get('results/student/{studentId}', [ResultApiController::class, 'getStudentResults']);
    Route::get('results/class/{classId}', [ResultApiController::class, 'getClassResults']);
    Route::get('results/subject/{subjectId}', [ResultApiController::class, 'getSubjectResults']);
    Route::post('results/publish', [ResultApiController::class, 'publishResults']);
    Route::get('results/analytics', [ResultApiController::class, 'getAnalytics']);
});
