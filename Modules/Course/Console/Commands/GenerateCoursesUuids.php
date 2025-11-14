<?php

namespace Modules\Course\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Modules\Course\App\Models\Course;

class GenerateCoursesUuids extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'courses:generate-uuids';

    /**
     * The description of the console command.
     *
     * @var string
     */
    protected $description = 'Generate UUIDs for all courses that have null UUIDs';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting to generate UUIDs for courses...');

        // Find all courses with null UUID
        $coursesWithoutUuid = Course::whereNull('uuid')->get();

        if ($coursesWithoutUuid->isEmpty()) {
            $this->info('All courses already have UUIDs!');
            return Command::SUCCESS;
        }

        $count = 0;
        foreach ($coursesWithoutUuid as $course) {
            $course->update(['uuid' => (string) Str::uuid()]);
            $count++;
            $this->line("Generated UUID for course ID {$course->id}: {$course->uuid}");
        }

        $this->info("Successfully generated UUIDs for {$count} courses!");
        return Command::SUCCESS;
    }
}
