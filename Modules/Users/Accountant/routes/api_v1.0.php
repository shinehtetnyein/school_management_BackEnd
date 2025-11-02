<?php

use Illuminate\Support\Facades\Route;
use Modules\users\Accountant\app\Http\Controllers\AccountantController;

Route::apiResource('accountants', AccountantController::class)->names('accountant');

