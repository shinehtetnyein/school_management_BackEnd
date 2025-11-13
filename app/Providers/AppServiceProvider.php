<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Load migrations from module directories
        $this->loadMigrationsFrom([
            base_path('Modules/Course/database/migrations'),
            base_path('Modules/ClassRoom/database/migrations'),
            base_path('Modules/Subject/database/migrations'),
            base_path('Modules/Users/database/migrations'),
            base_path('Modules/Departments/database/migrations'),
            base_path('Modules/Exams/database/migrations'),
            base_path('Modules/Results/database/migrations'),
            base_path('Modules/AcademicYears/database/migrations'),
            base_path('Modules/TimeTable/database/migrations'),
            base_path('Modules/Attendance/database/migrations'),
            base_path('Modules/Homework/database/migrations'),
            base_path('Modules/Authentication/database/migrations'),
        ]);

        Route::prefix('api')
        ->middleware('api')
        ->group(base_path('routes/api.php'));
    }
}
