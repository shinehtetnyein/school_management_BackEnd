<?php

namespace Modules\Exams\app\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Exams\Services\ExamApiServiceInterface;
use Modules\Exams\Services\ExamApiService;

class ExamsServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(ExamApiServiceInterface::class, ExamApiService::class);
    }

    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot()
    {
    }
}