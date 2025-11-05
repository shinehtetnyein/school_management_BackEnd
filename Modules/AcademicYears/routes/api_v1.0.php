<?php

use Illuminate\Support\Facades\Route;
use Modules\AcademicYears\App\Http\Controllers\AcademicDetailsController;
use Modules\AcademicYears\App\Http\Controllers\AcademicYearsController;

    // Academic Year Routes - using apiResource for CRUD
    Route::apiResource('years', AcademicYearsController::class);

    // Additional custom routes for AcademicYear
        Route::get('current', [AcademicYearsController::class, 'getCurrent']);
        Route::post('{id}/set-current', [AcademicYearsController::class, 'setCurrent']);

    // Academic Detail Routes
        Route::get('/', [AcademicDetailsController::class, 'index']);
        Route::post('assign-user', [AcademicDetailsController::class, 'assignUser']);
        Route::delete('remove-user', [AcademicDetailsController::class, 'removeUser']);
        Route::get('user/{userId}/academic-years', [AcademicDetailsController::class, 'getUserAcademicYears']);
        Route::get('academic-year/{academicYearId}/users', [AcademicDetailsController::class, 'getAcademicYearUsers']);
