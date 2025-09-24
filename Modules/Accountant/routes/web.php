<?php

use Illuminate\Support\Facades\Route;
use Modules\Accountant\Http\Controllers\AccountantController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('accountants', AccountantController::class)->names('accountant');
});
