<?php
// Modules/Subject/database/seeders/CourseSubjectSeeder.php

namespace Modules\Subject\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Course\App\Models\Course;
use Modules\Subject\App\Models\Subject;

class CourseSubjectSeeder extends Seeder
{
    public function run(): void
    {
        if (app()->environment('production')) {
            return;
        }

        $this->command->info('Creating Myanmar curriculum course-subject relationships...');

        // Get all courses and subjects
        $courses = Course::all();
        $subjects = Subject::all();

        if ($courses->isEmpty() || $subjects->isEmpty()) {
            $this->command->error('Courses or subjects not found. Please run CourseSeeder and SubjectSeeder first.');
            return;
        }

        $relationshipsCreated = 0;

        // Assign subjects to courses based on Myanmar curriculum
        foreach ($courses as $course) {
            $relevantSubjects = $this->getRelevantSubjectsForGrade($course->course_name, $subjects);

            foreach ($relevantSubjects as $subject) {
                // Check if relationship already exists
                if (!$course->subjects()->where('subject_id', $subject->id)->exists()) {
                    $course->subjects()->attach($subject->id);
                    $relationshipsCreated++;
                }
            }
        }

        $this->command->info("Created {$relationshipsCreated} course-subject relationships for Myanmar curriculum.");
    }

    private function getRelevantSubjectsForGrade(string $courseName, $subjects)
    {
        $courseName = strtolower($courseName);

        // Primary Education (Grade 1-5)
        if (str_contains($courseName, 'grade 1') || str_contains($courseName, 'grade 2') ||
            str_contains($courseName, 'grade 3') || str_contains($courseName, 'grade 4') ||
            str_contains($courseName, 'grade 5')) {
            return $subjects->whereIn('subject_code', ['MM-PRI', 'ENG-PRI', 'MATH-PRI', 'SCI-PRI', 'SOC-PRI']);
        }

        // Middle School (Grade 6-9)
        if (str_contains($courseName, 'grade 6') || str_contains($courseName, 'grade 7') ||
            str_contains($courseName, 'grade 8') || str_contains($courseName, 'grade 9')) {
            return $subjects->whereIn('subject_code', [
                'MM-MID', 'ENG-MID', 'MATH-MID', 'PHY-MID',
                'CHEM-MID', 'BIO-MID', 'HIST-MID', 'GEO-MID'
            ]);
        }

        // High School Arts Stream (Grade 10-12)
        if (str_contains($courseName, 'arts')) {
            return $subjects->whereIn('subject_code', [
                'MM-ART', 'ENG-ART', 'ECON-ART', 'HIST-ART', 'GEO-ART', 'PSY-ART'
            ]);
        }

        // High School Science Stream (Grade 10-12)
        if (str_contains($courseName, 'science')) {
            return $subjects->whereIn('subject_code', [
                'MM-SCI', 'ENG-SCI', 'MATH-SCI', 'PHY-SCI', 'CHEM-SCI', 'BIO-SCI'
            ]);
        }

        return collect(); // Return empty collection if no match
    }
}
