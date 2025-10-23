<?php

namespace Modules\Courses\App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Modules\Courses\Services\CourseApiServiceInterface;
use Modules\Courses\Services\Implementations\CourseApiService;

class CourseServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Note: The implementation class seems to be StudentApiService based on the provided context files.
        $this->app->bind(CourseApiServiceInterface::class, CourseApiService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');

        Route::prefix('api/v1')
            ->middleware('api')
            ->group(function () {
                require __DIR__ . '/../../routes/api_v1.0.php';
            });
    }
}
