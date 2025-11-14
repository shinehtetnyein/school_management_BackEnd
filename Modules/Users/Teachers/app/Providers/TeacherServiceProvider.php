<?php

namespace Modules\Users\Teachers\App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Modules\Users\Teachers\Services\Implementations\TeacherApiService;
use Modules\Users\Teachers\Services\TeacherApiServiceInterface;

class TeacherServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(TeacherApiServiceInterface::class, TeacherApiService::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');
        Route::prefix('api')
            ->middleware('api')
            ->group(function () {
                require __DIR__ . '/../../routes/api_v1.0.php';
            });
    }
}
