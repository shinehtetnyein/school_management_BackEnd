<?php

use Illuminate\Support\Facades\Route;
use Modules\Users\User\app\Http\Controllers\UserApiController;

Route::apiResource('/users', UserApiController::class);
