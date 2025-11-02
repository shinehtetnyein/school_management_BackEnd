<?php

use Illuminate\Support\Facades\Route;
use Modules\Users\Students\app\Http\Controllers\StudentController;

    Route::apiResource('students', StudentController::class)->names('student');

