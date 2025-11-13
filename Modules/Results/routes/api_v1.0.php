<?php

use Illuminate\Support\Facades\Route;
use Modules\Results\app\Http\Controllers\ResultController;

    Route::apiResource('results', ResultController::class);



