<?php

use Illuminate\Support\Facades\Route;
use Modules\Accountant\Http\Controllers\AccountantController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('accountants', AccountantController::class)->names('accountant');
});
