<?php

use Illuminate\Support\Facades\Route;
use Modules\Results\app\Http\Controllers\ResultController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('results', ResultController::class);
});



