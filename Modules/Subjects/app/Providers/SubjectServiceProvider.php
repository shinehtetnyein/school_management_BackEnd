<?php

namespace Modules\Subjects\App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class SubjectServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Bind your interfaces to implementations here
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
