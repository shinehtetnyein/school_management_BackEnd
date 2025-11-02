<?php

use Illuminate\Support\Facades\Route;
use Modules\Results\app\Http\Controllers\ResultApiController;

    Route::apiResource('results', ResultApiController::class);



