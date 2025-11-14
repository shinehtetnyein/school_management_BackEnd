<?php
// Modules/Subject/routes/api.php

use Illuminate\Support\Facades\Route;
use Modules\Subject\App\Http\Controllers\SubjectApiController;
use Modules\Subject\App\Http\Controllers\StudentSubjectController;

// Main subject resource routes using UUID
Route::apiResource('subjects', SubjectApiController::class, [
    'parameters' => ['subject' => 'uuid']
]);

// Additional Subject Routes
Route::prefix('subjects')->group(function () {
    Route::get('course/{courseUuid}', [SubjectApiController::class, 'getByCourse']);
    Route::get('level/{classLevel}', [SubjectApiController::class, 'getByLevel']);
    Route::get('search', [SubjectApiController::class, 'search']);
});

// Student Subject Routes
Route::prefix('students')->group(function () {
    Route::get('{studentUuid}/subjects', [StudentSubjectController::class, 'getStudentSubjects']);
    Route::get('subjects/{uuid}/teachers', [StudentSubjectController::class, 'getSubjectTeachers']);
});

