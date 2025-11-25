<?php

use Illuminate\Support\Facades\Route;
use Modules\Exams\app\Http\Controllers\ExamController;

Route::apiResource('exams', ExamController::class);
Route::get('exams/{id}/results', [ExamController::class, 'getResults']);
