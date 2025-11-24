<?php

use Illuminate\Support\Facades\Route;
use Modules\Homework\app\Http\Controllers\HomeworkController;

use App\Console\Enums\Role;

Route::prefix('v1')->group(function () {
    Route::middleware(['auth:sanctum'])->group(function () {
        Route::get('homeworks', [HomeworkController::class, 'index']);
        Route::get('homeworks/{id}', [HomeworkController::class, 'show']);
        Route::get('homeworks/class/{classId}', [HomeworkController::class, 'getClassHomeworks']);
        Route::get('homeworks/student/{studentId}', [HomeworkController::class, 'getStudentHomeworks']);
    });

    Route::middleware(['auth:sanctum', 'role:' . Role::TEACHER->label() . '|' . Role::ADMIN->label() . '|' . Role::ROOT_ADMIN->label()])->group(function () {
        Route::post('homeworks', [HomeworkController::class, 'store']);
        Route::put('homeworks/{id}', [HomeworkController::class, 'update']);
        Route::delete('homeworks/{id}', [HomeworkController::class, 'destroy']);
    });
});
