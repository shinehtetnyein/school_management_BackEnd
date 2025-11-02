<?php

use Illuminate\Support\Facades\Route;
use Modules\Exams\app\Http\Controllers\ExamController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('exams', ExamController::class);

    // Additional exam routes
    Route::get('exams/{id}/results', [ExamController::class, 'getResults']);
    Route::post('exams/{id}/submit', [ExamController::class, 'submitExam']);
    Route::get('exams/{id}/schedule', [ExamController::class, 'getSchedule']);
    Route::post('exams/{id}/grade', [ExamController::class, 'gradeExam']);
});
