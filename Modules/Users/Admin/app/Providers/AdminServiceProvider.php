<?php
// Modules/Users/Admin/app/Providers/AdminServiceProvider.php

namespace Modules\Users\Admin\App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Modules\Users\Admin\Services\AdminApiServiceInterface;
use Modules\Users\Admin\Services\Implementations\AdminApiService;

class AdminServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Bind service interface to implementation
        $this->app->bind(
            AdminApiServiceInterface::class,
            AdminApiService::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Load routes
        Route::prefix('api/v1')
            ->middleware('api')
            ->group(function () {
                require __DIR__ . '/../../routes/api_v1.0.php';
            });
    }
}
