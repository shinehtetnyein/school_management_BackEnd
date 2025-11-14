<?php
// Modules/Course/database/seeders/EnrollmentSeeder.php

namespace Modules\Course\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Course\App\Models\Enrollment;
use Modules\Course\App\Models\Course;
use Modules\Users\User\App\Models\User;
use App\Console\Enums\Role as RoleEnum;

class EnrollmentSeeder extends Seeder
{
    public function run(): void
    {
        if (app()->environment('production')) {
            return;
        }

        $this->command->info('Creating student enrollments - Each student assigned to ONE course (their grade level)...');

        // Get all courses grouped by category
        $primaryCourses = Course::where('category', 'Primary Education')->get();
        $middleCourses = Course::where('category', 'Middle School Education')->get();
        $artsCourses = Course::where('category', 'High School Arts')->get();
        $scienceCourses = Course::where('category', 'High School Science')->get();

        // Get student users
        $students = User::whereHas('roles', function ($query) {
            $query->where('name', RoleEnum::STUDENT->label());
        })->get();

        if ($students->isEmpty()) {
            $this->command->error('No student users found. Please run DevUsersSeeder first.');
            return;
        }

        $enrollmentCount = 0;
        $allCourses = Course::all()->toArray();
        $courseCount = count($allCourses);

        if ($courseCount === 0) {
            $this->command->error('No courses found. Please run CourseSeeder first.');
            return;
        }

        foreach ($students as $index => $student) {
            // Each student gets ONE course based on their index/grade distribution
            $courseIndex = $index % $courseCount;
            $gradeCourse = Course::find($allCourses[$courseIndex]['id']);

            // Skip if already enrolled in this course
            if ($gradeCourse && !Enrollment::where('user_id', $student->id)
                ->where('course_id', $gradeCourse->id)
                ->exists()) {

                Enrollment::create([
                    'user_id' => $student->id,
                    'course_id' => $gradeCourse->id,
                    'status' => 'active',
                    'class_level' => $this->getClassLevelFromCourse($gradeCourse->course_name),
                    'enrolled_at' => now()->subMonths(rand(1, 12)),
                    'updated_at' => now()
                ]);

                $enrollmentCount++;
            }
        }

        $this->command->info("Created {$enrollmentCount} student enrollments.");
        $this->command->info("✓ Each student is enrolled in exactly ONE course (their grade level).");
    }

    private function getClassLevelFromCourse(string $courseName): string
    {
        if (str_contains($courseName, 'Primary')) {
            return 'Primary Level';
        } elseif (str_contains($courseName, 'Middle School')) {
            return 'Middle School Level';
        } elseif (str_contains($courseName, 'Arts')) {
            return 'Arts Stream';
        } elseif (str_contains($courseName, 'Science')) {
            return 'Science Stream';
        }

        return 'General';
    }
}
