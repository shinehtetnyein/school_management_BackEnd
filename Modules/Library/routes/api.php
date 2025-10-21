<?php

use Illuminate\Support\Facades\Route;
use Modules\Library\Http\Controllers\LibraryController;

Route::prefix('api')->group(function () {
    Route::get('books', [LibraryController::class, 'index']);
    Route::post('books', [LibraryController::class, 'store']);
});
