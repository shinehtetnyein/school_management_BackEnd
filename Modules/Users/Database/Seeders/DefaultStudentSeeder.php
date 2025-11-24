<?php

namespace Modules\Users\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Modules\Users\User\App\Models\User;
use App\Console\Enums\Role;

class DefaultStudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $studentsData = [
            [
                'first_name' => 'Alice',
                'last_name' => 'Johnson',
                'roll_no' => 'STU-2001',
                'enrollment_date' => '2025-06-01',
                'email' => 'alice.johnson@school.com',
                'phone_no'   => '0951234567',
                'address' => '123 Main Street',
                'city' => 'Yangon',
                'country' => 'Myanmar',
                'date_of_birth' => '2008-05-15',
                'gender' => 'female',
                'nrc' => '12/ABCDE(N)123456',
                'religion' => 'Buddhist',
                'mother_tongue' => 'Burmese',
                'language' => 'English',
                'status' => 'active',
                'courses' => [1, 2, 3],
                'classroom_id' => 1,
                'section_id' => 1,
                'subjects' => [1, 2, 3, 4],
            ],
            [
                'first_name' => 'Bob',
                'last_name' => 'Smith',
                'roll_no' => 'STU-2002',
                'enrollment_date' => '2025-06-02',
                'email' => 'bob.smith@school.com',
                'phone_no' => '0952345678',
                'address' => '456 Oak Avenue',
                'city' => 'Mandalay',
                'country' => 'Myanmar',
                'date_of_birth' => '2008-08-20',
                'gender' => 'male',
                'nrc' => '12/FGHIJ(N)234567',
                'religion' => 'Buddhist',
                'mother_tongue' => 'Burmese',
                'language' => 'English',
                'status' => 'active',
                'courses' => [1, 4, 5],
                'classroom_id' => 1,
                'section_id' => 2,
                'subjects' => [2, 3, 5, 6],
            ],
            [
                'first_name' => 'Carol',
                'last_name' => 'Williams',
                'roll_no' => 'STU-2003',
                'enrollment_date' => '2025-06-03',
                'email' => 'carol.williams@school.com',
                'phone_no' => '0953456789',
                'address' => '789 Pine Road',
                'city' => 'Naypyidaw',
                'country' => 'Myanmar',
                'date_of_birth' => '2008-03-10',
                'gender' => 'female',
                'nrc' => '12/KLMNO(N)345678',
                'religion' => 'Christian',
                'mother_tongue' => 'Burmese',
                'language' => 'English',
                'status' => 'active',
                'courses' => [2, 3, 6],
                'classroom_id' => 2,
                'section_id' => 3,
                'subjects' => [1, 4, 7, 8],
            ],
            [
                'first_name' => 'David',
                'last_name' => 'Brown',
                'roll_no' => 'STU-2004',
                'enrollment_date' => '2025-06-04',
                'email' => 'david.brown@school.com',
                'phone_no' => '0954567890',
                'address' => '321 Elm Street',
                'city' => 'Bagan',
                'country' => 'Myanmar',
                'date_of_birth' => '2008-11-25',
                'gender' => 'male',
                'nrc' => '12/PQRST(N)456789',
                'religion' => 'Muslim',
                'mother_tongue' => 'Burmese',
                'language' => 'English',
                'status' => 'active',
                'courses' => [1, 3, 5, 7],
                'classroom_id' => 2,
                'section_id' => 4,
                'subjects' => [2, 5, 9, 10],
            ],
            [
                'first_name' => 'Emma',
                'last_name' => 'Davis',
                'roll_no' => 'STU-2005',
                'enrollment_date' => '2025-06-05',
                'email' => 'emma.davis@school.com',
                'phone_no' => '0955678901',
                'address' => '654 Maple Drive',
                'city' => 'Inle Lake',
                'country' => 'Myanmar',
                'date_of_birth' => '2008-07-18',
                'gender' => 'female',
                'nrc' => '12/UVWXY(N)567890',
                'religion' => 'Hindu',
                'mother_tongue' => 'Shan',
                'language' => 'English',
                'status' => 'active',
                'courses' => [2, 4, 6, 8],
                'classroom_id' => 3,
                'section_id' => 5,
                'subjects' => [3, 6, 8, 11],
            ],
        ];

        foreach ($studentsData as $data) {
            // Extract relationships data
            $courses = $data['courses'];
            $subjects = $data['subjects'];
            $classroomId = $data['classroom_id'];
            $sectionId = $data['section_id'];

            // Extract and remove relationship data from array
            $enrollmentDate = $data['enrollment_date'] ?? null;
            unset($data['courses'], $data['subjects'], $data['classroom_id'], $data['section_id'], $data['enrollment_date']);

            // Create the user
            $student = User::create([
                ...$data,
                'password' => Hash::make('password'),
                'uuid' => \Illuminate\Support\Str::uuid(),
            ]);

            // Assign student role
            $student->assignRole(Role::STUDENT->value);

            // Attach to classroom
            $student->classroom()->attach($classroomId);

            // Attach to section
            $student->section()->attach($sectionId);

            // Attach to courses with provided enrollment_date if available
            foreach ($courses as $courseId) {
                $student->courses()->attach($courseId, [
                    'enrollment_date' => $enrollmentDate ?? now()->format('Y-m-d'),
                    'status' => 'active',
                ]);
            }

            // Attach to subjects
            foreach ($subjects as $subjectId) {
                $student->subjects()->attach($subjectId);
            }

            $this->command->info("Student created: {$student->first_name} {$student->last_name}");
        }

        $this->command->info('5 default students created successfully with courses, classrooms, sections, and subjects.');
    }
}
