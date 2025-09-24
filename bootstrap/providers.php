<?php

return [
    App\Providers\AppServiceProvider::class,
    Modules\Accountant\app\Providers\AccountantServiceProvider::class,
    Modules\Admin\app\Providers\AdminServiceProvider::class,
    Modules\Authentication\app\Providers\AuthenticationServiceProvider::class,
    Modules\Parent\app\Providers\ParentServiceProvider::class,
    Modules\Student\app\Providers\StudentServiceProvider::class,
    Modules\Users\User\App\Providers\UserServiceProvider::class,
];
