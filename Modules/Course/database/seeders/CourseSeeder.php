<?php
// Modules/Course/database/seeders/CourseSeeder.php

namespace Modules\Course\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Course\App\Models\Course;

class CourseSeeder extends Seeder
{
    public function run(): void
    {
        if (app()->environment('production')) {
            $this->command->warn('CourseSeeder will not run in production.');
            return;
        }

        $this->command->info('Creating Myanmar high school courses (Grade 1-12)...');

        $courses = [
            // Primary Level (Grade 1-5)
            [
                'course_name' => 'Grade 1 - Primary',
                'description' => 'မူလတန်း ပထမတန်း - Basic education for young learners',
                'category' => 'Primary Education'
            ],
            [
                'course_name' => 'Grade 2 - Primary',
                'description' => 'မူလတန်း ဒုတိယတန်း - Elementary education foundation',
                'category' => 'Primary Education'
            ],
            [
                'course_name' => 'Grade 3 - Primary',
                'description' => 'မူလတန်း တတိယတန်း - Intermediate primary education',
                'category' => 'Primary Education'
            ],
            [
                'course_name' => 'Grade 4 - Primary',
                'description' => 'မူလတန်း စတုတ္ထတန်း - Advanced primary education',
                'category' => 'Primary Education'
            ],
            [
                'course_name' => 'Grade 5 - Primary',
                'description' => 'မူလတန်း ပဉ္စမတန်း - Final year of primary education',
                'category' => 'Primary Education'
            ],

            // Middle School Level (Grade 6-9)
            [
                'course_name' => 'Grade 6 - Middle School',
                'description' => 'အလယ်တန်း ဆဋ္ဌမတန်း - Beginning of middle school education',
                'category' => 'Middle School Education'
            ],
            [
                'course_name' => 'Grade 7 - Middle School',
                'description' => 'အလယ်တန်း သတ္တမတန်း - Middle school intermediate level',
                'category' => 'Middle School Education'
            ],
            [
                'course_name' => 'Grade 8 - Middle School',
                'description' => 'အလယ်တန်း အဋ္ဌမတန်း - Middle school advanced level',
                'category' => 'Middle School Education'
            ],
            [
                'course_name' => 'Grade 9 - Middle School',
                'description' => 'အလယ်တန်း နဝမတန်း - Final year of middle school',
                'category' => 'Middle School Education'
            ],

            // High School Level (Grade 10-12) - Arts Stream
            [
                'course_name' => 'Grade 10 - Arts Stream',
                'description' => 'အထက်တန်း ဒသမတန်း - Arts stream foundation year',
                'category' => 'High School Arts'
            ],
            [
                'course_name' => 'Grade 11 - Arts Stream',
                'description' => 'အထက်တန်း ဧကာဒသမတန်း - Arts stream intermediate year',
                'category' => 'High School Arts'
            ],
            [
                'course_name' => 'Grade 12 - Arts Stream',
                'description' => 'အထက်တန်း ဒွါဒသမတန်း - Arts stream final year for matriculation',
                'category' => 'High School Arts'
            ],

            // High School Level (Grade 10-12) - Science Stream
            [
                'course_name' => 'Grade 10 - Science Stream',
                'description' => 'အထက်တန်း ဒသမတန်း - Science stream foundation year',
                'category' => 'High School Science'
            ],
            [
                'course_name' => 'Grade 11 - Science Stream',
                'description' => 'အထက်တန်း ဧကာဒသမတန်း - Science stream intermediate year',
                'category' => 'High School Science'
            ],
            [
                'course_name' => 'Grade 12 - Science Stream',
                'description' => 'အထက်တန်း ဒွါဒသမတန်း - Science stream final year for matriculation',
                'category' => 'High School Science'
            ]
        ];

        foreach ($courses as $courseData) {
            Course::firstOrCreate(
                ['course_name' => $courseData['course_name']],
                $courseData
            );
        }

        $this->command->info('Myanmar high school courses created successfully.');
        $this->command->info('Total courses: ' . count($courses));
        $this->command->info('Primary (Grade 1-5): 5 courses');
        $this->command->info('Middle School (Grade 6-9): 4 courses');
        $this->command->info('High School Arts (Grade 10-12): 3 courses');
        $this->command->info('High School Science (Grade 10-12): 3 courses');
    }
}
