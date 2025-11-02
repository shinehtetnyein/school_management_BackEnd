<?php

use Illuminate\Support\Facades\Route;
use Modules\Users\Stuff\app\Http\Controllers\StuffController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('stuffs', StuffController::class)->names('stuff');
});
