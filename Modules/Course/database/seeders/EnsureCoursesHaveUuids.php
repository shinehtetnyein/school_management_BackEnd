<?php

namespace Modules\Course\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Modules\Course\App\Models\Course;

class EnsureCoursesHaveUuids extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all courses without UUIDs
        $coursesWithoutUuid = Course::whereNull('uuid')->get();

        if ($coursesWithoutUuid->isEmpty()) {
            $this->command->info('All courses already have UUIDs!');
            return;
        }

        foreach ($coursesWithoutUuid as $course) {
            $course->update(['uuid' => (string) Str::uuid()]);
            $this->command->line("Assigned UUID to course: {$course->course_name}");
        }

        $this->command->info("Successfully assigned UUIDs to {$coursesWithoutUuid->count()} courses!");
    }
}
