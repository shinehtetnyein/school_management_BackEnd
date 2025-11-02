<?php

use Illuminate\Support\Facades\Route;
use Modules\Homework\app\Http\Controllers\HomeworkController;

Route::prefix('api')->group(function () {
    Route::get('homeworks', [HomeworkController::class, 'index']);
    Route::post('homeworks', [HomeworkController::class, 'store']);
});
