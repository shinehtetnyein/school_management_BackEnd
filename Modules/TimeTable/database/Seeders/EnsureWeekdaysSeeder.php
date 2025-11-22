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

class EnsureWeekdaysSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (app()->environment('production')) {
            $this->command->warn('EnsureWeekdaysSeeder will not run in production.');
            return;
        }

        $days = ['Monday','Tuesday','Wednesday','Thursday','Friday'];

        $classrooms = ClassRoom::all();
        if ($classrooms->isEmpty()) {
            $this->command->warn('No classrooms found; skipping EnsureWeekdaysSeeder.');
            return;
        }

        $created = 0;

        foreach ($classrooms as $classroom) {
            // choose a section belonging to the classroom, or first available
            $section = Section::where('classroom_id', $classroom->id)->first() ?? Section::first();
            $course = Course::first();
            $subject = Subject::first();
            $teacher = User::teachers()->first() ?? User::first();

            foreach ($days as $day) {
                // If there's already an entry for this classroom + day, skip
                $exists = TimeTable::where('classroom_id', $classroom->id)
                    ->where('day_of_week', $day)
                    ->exists();

                if ($exists) continue;

                $data = [
                    'classroom_id' => $classroom->id,
                    'section_id' => $section->id ?? null,
                    'course_id' => $course->id ?? null,
                    'day_of_week' => $day,
                    // default placeholder times: 09:00 - 15:00 per your request
                    'start_time' => '09:00',
                    'end_time' => '15:00',
                    'subject_id' => $subject->id ?? null,
                    'teacher_id' => $teacher->id ?? null,
                    'room_number' => $classroom->room_number ?? null,
                    'notes' => 'Auto-generated placeholder',
                    'status' => 'active',
                ];

                try {
                    // compute duration
                    $data['duration_minutes'] = Carbon::parse($data['end_time'])->diffInMinutes(Carbon::parse($data['start_time']));
                } catch (\Exception $e) {
                    $data['duration_minutes'] = null;
                }

                try {
                    TimeTable::create($data);
                    $created++;
                    $this->command->info("Created placeholder timetable for classroom {$classroom->id} on {$day}");
                } catch (\Exception $e) {
                    $this->command->error('Failed to create placeholder timetable: ' . $e->getMessage());
                }
            }
        }

        $this->command->info((string)$created . ' placeholder timetable entries created for weekdays.');
    }
}
