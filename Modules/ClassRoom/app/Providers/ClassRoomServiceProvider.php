<?php

namespace Modules\ClassRoom\app\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\ClassRoom\Services\ClassRoomApiServiceInterface;
use Modules\ClassRoom\Services\SectionApiServiceInterface;
use Modules\ClassRoom\Services\SectionApiService;

class ClassRoomServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(ClassRoomApiServiceInterface::class, ClassRoomApiService::class);
        $this->app->bind(SectionApiServiceInterface::class, SectionApiService::class);
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