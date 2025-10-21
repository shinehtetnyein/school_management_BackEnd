<?php

use Illuminate\Support\Facades\Route;
use Modules\Stuff\Http\Controllers\StuffController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('stuffs', StuffController::class)->names('stuff');
});
