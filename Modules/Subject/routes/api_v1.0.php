<?php
// Modules/Subject/routes/api.php

use Illuminate\Support\Facades\Route;
use Modules\Subject\App\Http\Controllers\SubjectApiController;

Route::apiResource('subjects', SubjectApiController::class);

// Additional Subject Routes
Route::prefix('subjects')->group(function () {
    Route::get('course/{courseId}', [SubjectApiController::class, 'getByCourse']);
    Route::get('level/{classLevel}', [SubjectApiController::class, 'getByLevel']);
    Route::get('search', [SubjectApiController::class, 'search']);
});
