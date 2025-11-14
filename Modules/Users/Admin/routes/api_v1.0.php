<?php
// Modules/Users/Admin/routes/api_v1.0.php

use Illuminate\Support\Facades\Route;
use Modules\Users\Admin\App\Http\Controllers\AdminController;
use App\Console\Enums\Role;

Route::middleware('auth:sanctum')->group(function () {
    // User CRUD endpoints
    Route::apiResource('admin/users', AdminController::class);

    // Role assignment endpoints
    Route::post('admin/users/{id}/assign-role', [AdminController::class, 'assignRole']);
    Route::post('admin/users/{id}/remove-role', [AdminController::class, 'removeRole']);

    // Permission endpoints
    Route::post('admin/users/{id}/grant-permission', [AdminController::class, 'grantPermission']);
    Route::post('admin/users/{id}/revoke-permission', [AdminController::class, 'revokePermission']);

    // Get users by role
    Route::get('admin/users/role/{roleName}', [AdminController::class, 'getUsersByRole']);
});
