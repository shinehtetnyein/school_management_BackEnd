<?php

use Illuminate\Support\Facades\Route;
use Modules\Librarian\Http\Controllers\LibrarianController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('librarians', LibrarianController::class)->names('librarian');
});
