<?php

return [
    App\Providers\AppServiceProvider::class,
    Nwidart\Modules\LaravelModulesServiceProvider::class,
    Modules\Authentication\App\Providers\AuthenticationServiceProvider::class,
    Modules\Users\User\App\Providers\UserServiceProvider::class,
    Modules\Users\Teacher\App\Providers\TeacherServiceProvider::class,
    Modules\Attendance\App\Providers\AttendanceServiceProvider::class,
    Modules\Users\Accountant\App\Providers\AccountantServiceProvider::class,
    Modules\Users\Librarian\App\Providers\LibrarianServiceProvider::class,
    Modules\Courses\App\Providers\CourseServiceProvider::class,
    Modules\Results\App\Providers\ResultServiceProvider::class,
    Modules\Subjects\App\Providers\SubjectServiceProvider::class,
    Modules\Departments\app\Providers\DepartmentServiceProvider::class,
    Modules\Exams\app\Providers\ExamsServiceProvider::class,
    Modules\Library\app\Providers\LibraryServiceProvider::class,
    Modules\Homework\app\Providers\HomeworkServiceProvider::class,
];
