<?php
// Modules/Subject/routes/api.php

use Illuminate\Support\Facades\Route;
use Modules\Subject\App\Http\Controllers\SubjectApiController;

Route::prefix('subjects')->group(function () {
    // Subject CRUD Routes
    Route::apiResource('/', SubjectApiController::class)->parameters(['' => 'subject']);

    // Additional Subject Routes
    Route::get('course/{courseId}', [SubjectApiController::class, 'getByCourse']);
    Route::get('level/{classLevel}', [SubjectApiController::class, 'getByLevel']);
    Route::get('search', [SubjectApiController::class, 'search']);
});
