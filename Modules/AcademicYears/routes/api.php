<?php

use Illuminate\Support\Facades\Route;
use Modules\AcademicYears\Http\Controllers\AcademicYearsController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('academicyears', AcademicYearsController::class)->names('academicyears');
});
