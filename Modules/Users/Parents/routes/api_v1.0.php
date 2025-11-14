<?php

use Illuminate\Support\Facades\Route;
use Modules\Users\Parents\App\Http\Controllers\ParentController;

Route::apiResource('parents', ParentController::class)->names('parent');

