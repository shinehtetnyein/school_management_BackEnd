<?php

namespace Modules\TimeTable\app\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Modules\TimeTable\Services\TimeTableApiServiceInterface;
use Modules\TimeTable\Services\Implementations\TimeTableApiService;

class TimeTableServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(TimeTableApiServiceInterface::class, TimeTableApiService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');

        Route::prefix('api')
            ->middleware('api')
            ->group(function () {
                require __DIR__ . '/../../routes/api.php';
            });
    }
}
