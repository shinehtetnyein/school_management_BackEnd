<?php

namespace Modules\Users\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Console\Enums\Role;
use Modules\Users\User\App\Models\User;

class TeacherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $teachersData = [
            [
                'first_name' => 'John',
                'last_name' => 'Anderson',
                'email' => 'john.anderson@school.com',
                'phone_no' => '0971234567',
                'address' => '100 Teacher Lane',
                'city' => 'Yangon',
                'country' => 'Myanmar',
                'date_of_birth' => '1985-03-15',
                'gender' => 'male',
                'status' => 1,
                'subjects' => [1, 2], // Mathematics, Physics
                'courses' => [1, 2], // Algebra I, Geometry
            ],
            [
                'first_name' => 'Sarah',
                'last_name' => 'Mitchell',
                'email' => 'sarah.mitchell@school.com',
                'phone_no' => '0972345678',
                'address' => '200 Academy Road',
                'city' => 'Yangon',
                'country' => 'Myanmar',
                'date_of_birth' => '1988-07-22',
                'gender' => 'female',
                'status' => 1,
                'subjects' => [3, 4], // Chemistry, Biology
                'courses' => [3, 4], // Chemistry I, Biology I
            ],
            [
                'first_name' => 'Robert',
                'last_name' => 'Thompson',
                'email' => 'robert.thompson@school.com',
                'phone_no' => '0973456789',
                'address' => '300 Scholar Street',
                'city' => 'Mandalay',
                'country' => 'Myanmar',
                'date_of_birth' => '1982-11-10',
                'gender' => 'male',
                'status' => 1,
                'subjects' => [5, 6], // English, History
                'courses' => [5, 6], // Literature I, World History
            ],
            [
                'first_name' => 'Jennifer',
                'last_name' => 'Garcia',
                'email' => 'jennifer.garcia@school.com',
                'phone_no' => '0974567890',
                'address' => '400 Education Avenue',
                'city' => 'Naypyidaw',
                'country' => 'Myanmar',
                'date_of_birth' => '1990-05-18',
                'gender' => 'female',
                'status' => 1,
                'subjects' => [7, 8], // Geography, Economics
                'courses' => [7, 8], // Geography I, Economics I
            ],
            [
                'first_name' => 'Michael',
                'last_name' => 'Rodriguez',
                'email' => 'michael.rodriguez@school.com',
                'phone_no' => '0975678901',
                'address' => '500 Knowledge Drive',
                'city' => 'Bagan',
                'country' => 'Myanmar',
                'date_of_birth' => '1987-09-30',
                'gender' => 'male',
                'status' => 1,
                'subjects' => [2, 9], // Physics, Computer Science
                'courses' => [2, 9], // Geometry, Programming I
            ],
            [
                'first_name' => 'Amanda',
                'last_name' => 'Lee',
                'email' => 'amanda.lee@school.com',
                'phone_no' => '0976789012',
                'address' => '600 Wisdom Lane',
                'city' => 'Inle Lake',
                'country' => 'Myanmar',
                'date_of_birth' => '1991-01-25',
                'gender' => 'female',
                'status' => 1,
                'subjects' => [1, 10], // Mathematics, Art
                'courses' => [1, 10], // Algebra I, Art History
            ],
        ];

        foreach ($teachersData as $data) {
            // Extract relationships data
            $subjects = $data['subjects'] ?? [];
            $courses = $data['courses'] ?? [];

            // Remove relationship data from array
            unset($data['subjects'], $data['courses']);

            // Create the user
            $teacher = User::create([
                ...$data,
                'password' => Hash::make('password123'),
                'uuid' => \Illuminate\Support\Str::uuid(),
            ]);

            // Assign teacher role
            $teacher->assignRole(Role::TEACHER->label());

            // Attach to subjects
            foreach ($subjects as $subjectId) {
                $teacher->teachingSubjects()->attach($subjectId, [
                    'assigned_date' => now()->format('Y-m-d'),
                    'status' => 1,
                ]);
            }

            // Attach to courses
            foreach ($courses as $courseId) {
                $teacher->teachingCourses()->attach($courseId, [
                    'assigned_date' => now()->format('Y-m-d'),
                    'status' => 1,
                ]);
            }

            $this->command->info("Teacher created: {$teacher->first_name} {$teacher->last_name}");
        }

        $this->command->info('6 default teachers created successfully with subjects and courses assigned.');
    }
}
