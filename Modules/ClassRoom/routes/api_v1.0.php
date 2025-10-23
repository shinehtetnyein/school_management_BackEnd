<?php

use Illuminate\Support\Facades\Route;
use Modules\Subjects\app\Http\Controllers\SubjectApiController;

Route::apiResource('classrooms', SubjectApiController::class);

