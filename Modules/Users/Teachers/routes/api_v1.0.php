<?php

use Illuminate\Support\Facades\Route;
use Modules\Users\Teachers\app\Http\Controllers\TeacherController;

Route::middleware('auth:sanctum')->prefix('v1')->group(function () {
    // Basic CRUD
    Route::apiResource('teachers', TeacherController::class)->names('teacher');

    // Get teachers by department
    Route::get('teachers/department/{departmentId}', [TeacherController::class, 'getByDepartment']);

    // Get teachers for a subject
    Route::get('teachers/subject/{subjectId}', [TeacherController::class, 'getTeachersForSubject']);

    // Get teacher's assigned courses
    Route::get('teachers/{uuid}/courses', [TeacherController::class, 'getTeacherCourses']);

    // Get teacher's assigned subjects
    Route::get('teachers/{uuid}/subjects', [TeacherController::class, 'getTeacherSubjects']);

    // Assign subject to teacher
    Route::post('teachers/{uuid}/assign-subject', [TeacherController::class, 'assignSubject']);

    // Remove subject from teacher
    Route::delete('teachers/{uuid}/remove-subject/{subjectId}', [TeacherController::class, 'removeSubject']);

    // Assign course to teacher
    Route::post('teachers/{uuid}/assign-course', [TeacherController::class, 'assignCourse']);

    // Remove course from teacher
    Route::delete('teachers/{uuid}/remove-course/{courseId}', [TeacherController::class, 'removeCourse']);
});
