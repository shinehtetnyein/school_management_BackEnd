<?php
// Modules/Course/routes/api.php

use Illuminate\Support\Facades\Route;
use Modules\Course\App\Http\Controllers\CourseController;
use Modules\Course\App\Http\Controllers\StudentCourseController;
use Modules\Course\App\Http\Controllers\EnrollmentController;
use App\Console\Enums\Role;

// Public endpoints: list courses
Route::get('courses', [CourseController::class, 'index']);
Route::get('courses/{uuid}', [CourseController::class, 'show']);

// Authenticated endpoints - accessible by students and other users
Route::middleware(['auth:sanctum'])->group(function () {
    // View course subjects
    Route::get('courses/{uuid}/subjects', [CourseController::class, 'getCourseSubjects']);

    // View enrolled students in a course
    Route::get('courses/{uuid}/students', [CourseController::class, 'getEnrolledStudents']);

    // Check if student is enrolled
    Route::get('courses/{uuid}/is-student-enrolled/{studentUuid}', [CourseController::class, 'isStudentEnrolled']);

    // Student course endpoints
    Route::get('students/{studentUuid}/courses', [StudentCourseController::class, 'index']);
    Route::get('students/{studentUuid}/courses/active', [StudentCourseController::class, 'getActive']);
    Route::get('students/{studentUuid}/courses/{courseUuid}', [StudentCourseController::class, 'show']);
    Route::get('students/{studentUuid}/courses/status/filter', [StudentCourseController::class, 'getByStatus']);
    Route::get('students/{studentUuid}/courses/subject/filter', [StudentCourseController::class, 'getBySubject']);
    Route::get('students/{studentUuid}/courses/stats', [StudentCourseController::class, 'getStats']);

    // Enrollment CRUD routes
    Route::apiResource('enrollments', EnrollmentController::class);
});

// Admin protected endpoints for CRUD operations
Route::middleware(['auth:sanctum', 'role:' . Role::ADMIN->label() . '|' . Role::ROOT_ADMIN->label()])->group(function () {
    Route::post('courses', [CourseController::class, 'store']);
    Route::put('courses/{uuid}', [CourseController::class, 'update']);
    Route::delete('courses/{uuid}', [CourseController::class, 'destroy']);

    // Manage course subjects
    Route::post('courses/{uuid}/assign-subjects', [CourseController::class, 'assignSubjects']);
    Route::post('courses/{uuid}/add-subject', [CourseController::class, 'addSubject']);
    Route::delete('courses/{uuid}/remove-subject/{subjectId}', [CourseController::class, 'removeSubject']);

    // Manage student enrollments
    Route::post('courses/{uuid}/enroll-student', [CourseController::class, 'enrollStudent']);
    Route::delete('courses/{uuid}/remove-student/{studentUuid}', [CourseController::class, 'removeStudent']);
    Route::patch('courses/{uuid}/student/{studentUuid}/status', [CourseController::class, 'updateStudentStatus']);
});
