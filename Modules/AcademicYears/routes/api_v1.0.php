<?php

use Illuminate\Support\Facades\Route;
use Modules\AcademicYears\app\Http\Controllers\AcademicYearsController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('academic_years', AcademicYearsController::class);
});
