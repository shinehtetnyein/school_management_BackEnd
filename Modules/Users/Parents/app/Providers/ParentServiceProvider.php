<?php

namespace Modules\Users\Parents\App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Modules\Users\Parents\Services\Implementations\ParentApiService;
use Modules\Users\Parents\Services\ParentApiServiceInterface;

class ParentServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(ParentApiServiceInterface::class, ParentApiService::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');
        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'parent');
        Route::prefix('api/v1')
            ->middleware('api')
            ->group(function () {
                require __DIR__ . '/../../routes/api_v1.0.php';
            });
    }
}
