<?php

use Nwidart\Modules\Facades\Module;

return [
    App\Providers\AppServiceProvider::class,
    Nwidart\Modules\LaravelModulesServiceProvider::class,
    Modules\Authentication\App\Providers\AuthenticationServiceProvider::class,
    Modules\Users\User\App\Providers\UserServiceProvider::class,
    Modules\Attendance\App\Providers\AttendanceServiceProvider::class,
    Modules\AcademicYears\App\Providers\AcademicYearApiProvider::class,
    Modules\Course\App\Providers\CourseServiceProvider::class,
    Modules\Subject\App\Providers\SubjectServiceProvider::class,
    Modules\Users\Accountant\App\Providers\AccountantServiceProvider::class,
    Modules\Users\Librarian\App\Providers\LibrarianServiceProvider::class,
    Modules\Results\App\Providers\ResultServiceProvider::class,
    Modules\Departments\app\Providers\DepartmentServiceProvider::class,
    Modules\Exams\app\Providers\ExamsServiceProvider::class,
    Modules\Library\app\Providers\LibraryServiceProvider::class,
    Modules\Homework\app\Providers\HomeworkServiceProvider::class,
    Modules\Users\Students\app\Providers\StudentServiceProvider::class,
    Modules\Users\Admin\app\Providers\AdminServiceProvider::class,
    Modules\Users\Teachers\app\Providers\TeacherServiceProvider::class,
    Modules\Users\Parents\app\Providers\ParentServiceProvider::class,
];
