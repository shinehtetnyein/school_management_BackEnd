<?php

namespace Modules\TimeTable\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\TimeTable\app\Models\TimeTable;
use Modules\ClassRoom\app\Models\ClassRoom;
use Modules\ClassRoom\app\Models\Section;
use Modules\Course\app\Models\Course;
use Modules\Subject\App\Models\Subject;
use Modules\Users\User\App\Models\User;
use Carbon\Carbon;

class TimeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (app()->environment('production')) {
            $this->command->warn('TimeTableSeeder will not run in production.');
            return;
        }

        // Resolve related entities dynamically to avoid FK issues with hard-coded IDs
        $classroom = ClassRoom::first();
        if (!$classroom) {
            $this->command->warn('No classrooms found; skipping TimeTable seeding.');
            return;
        }

        $section = Section::where('classroom_id', $classroom->id)->first() ?? Section::first();
        $course = Course::first();
        $subject = Subject::first();
        $teacher = User::teachers()->first() ?? User::first();

        if (!$section || !$course) {
            $this->command->warn('Required related records (section or course) missing; skipping TimeTable seeding.');
            return;
        }

        $timeTables = [
            [
                'classroom_id' => $classroom->id,
                'section_id' => $section->id,
                'course_id' => $course->id,
                'day_of_week' => 'Monday',
                'start_time' => '08:00',
                'end_time' => '09:00',
                'subject_id' => $subject->id ?? null,
                'teacher_id' => $teacher->id ?? null,
                'room_number' => $classroom->room_number ?? null,
                'notes' => 'Mathematics class',
                'status' => 'active',
            ],
            [
                'classroom_id' => $classroom->id,
                'section_id' => $section->id,
                'course_id' => $course->id,
                'day_of_week' => 'Monday',
                'start_time' => '09:15',
                'end_time' => '10:15',
                'subject_id' => $subject->id ?? null,
                'teacher_id' => $teacher->id ?? null,
                'room_number' => $classroom->room_number ?? null,
                'notes' => 'English class',
                'status' => 'active',
            ],
            [
                'classroom_id' => $classroom->id,
                'section_id' => $section->id,
                'course_id' => $course->id,
                'day_of_week' => 'Tuesday',
                'start_time' => '08:00',
                'end_time' => '09:00',
                'subject_id' => $subject->id ?? null,
                'teacher_id' => $teacher->id ?? null,
                'room_number' => $classroom->room_number ?? null,
                'notes' => 'Science class',
                'status' => 'active',
            ],
        ];

        $created = 0;
        foreach ($timeTables as $data) {
            try {
                // compute duration in minutes between start_time and end_time
                if (!empty($data['start_time']) && !empty($data['end_time'])) {
                    try {
                        $duration = Carbon::parse($data['end_time'])->diffInMinutes(Carbon::parse($data['start_time']));
                        $data['duration_minutes'] = $duration;
                    } catch (\Exception $ex) {
                        // if parsing fails, leave duration null and log
                        $this->command->warn('Could not compute duration for timetable entry: ' . $ex->getMessage());
                    }
                }

                TimeTable::create($data);
                $created++;
                $this->command->info("Timetable entry created: {$data['day_of_week']} {$data['start_time']}-{$data['end_time']} (duration: " . ($data['duration_minutes'] ?? 'n/a') . " mins)");
            } catch (\Exception $e) {
                $this->command->error('Failed to create timetable entry: ' . $e->getMessage());
            }
        }

        $this->command->info((string)$created . ' timetable entries created successfully.');
    }
}
