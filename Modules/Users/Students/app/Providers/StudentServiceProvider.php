<?php

namespace Modules\Users\Students\App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Modules\Users\Students\Services\Implementations\StudentApiService;
use Modules\Users\Students\Services\StudentApiServiceInterface;

class StudentServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(StudentApiServiceInterface::class, StudentApiService::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');
        Route::prefix('api/v1')
            ->middleware('api') // Apply any middleware if needed
            ->group(function () {
                require __DIR__ . '/../../routes/api_v1.0.php';
            });
    }
}
