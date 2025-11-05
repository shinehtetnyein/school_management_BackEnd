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

        $this->command->info('Creating realistic student enrollments for Myanmar high school...');

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

        foreach ($students as $index => $student) {
            // Distribute students across different grade levels realistically
            if ($index < 10) {
                // First 10 students in Primary (Grade 1-5)
                $gradeCourse = $primaryCourses->where('course_name', 'like', '%Grade ' . ($index % 5 + 1) . '%')->first();
            } elseif ($index < 20) {
                // Next 10 students in Middle School (Grade 6-9)
                $gradeCourse = $middleCourses->where('course_name', 'like', '%Grade ' . (($index % 4) + 6) . '%')->first();
            } elseif ($index < 25) {
                // Next 5 students in Arts Stream (Grade 10-12)
                $gradeCourse = $artsCourses->where('course_name', 'like', '%Grade ' . (($index % 3) + 10) . '%')->first();
            } else {
                // Remaining students in Science Stream (Grade 10-12)
                $gradeCourse = $scienceCourses->where('course_name', 'like', '%Grade ' . (($index % 3) + 10) . '%')->first();
            }

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

        $this->command->info("Created {$enrollmentCount} realistic student enrollments.");
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
