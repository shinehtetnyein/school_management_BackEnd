<?php

use Illuminate\Support\Facades\Route;
use Modules\Exams\app\Http\Controllers\ExamController;
use App\Console\Enums\Role;

Route::prefix('v1')->group(function () {
    // Publicly authenticated routes
    Route::middleware(['auth:sanctum'])->group(function () {
        Route::get('exams', [ExamController::class, 'index']);
        Route::get('exams/{id}', [ExamController::class, 'show']);
        Route::get('exams/{id}/schedule', [ExamController::class, 'getSchedule']);

        // Submission by students
        Route::post('exams/{id}/submit', [ExamController::class, 'submitExam'])->middleware('role:' . Role::STUDENT->label());

        // Viewing results - teachers/admins can view, students may view their own via another endpoint if implemented
        Route::get('exams/{id}/results', [ExamController::class, 'getResults'])->middleware('role:' . Role::TEACHER->label() . '|' . Role::ADMIN->label() . '|' . Role::ROOT_ADMIN->label());
    });

    // Protected exam management (create/update/delete) - teachers and admins
    Route::middleware(['auth:sanctum', 'role:' . Role::TEACHER->label() . '|' . Role::ADMIN->label() . '|' . Role::ROOT_ADMIN->label()])->group(function () {
        Route::post('exams', [ExamController::class, 'store']);
        Route::put('exams/{id}', [ExamController::class, 'update']);
        Route::delete('exams/{id}', [ExamController::class, 'destroy']);

        // Grading is limited to teachers and admins
        Route::post('exams/{id}/grade', [ExamController::class, 'gradeExam']);
    });
});
