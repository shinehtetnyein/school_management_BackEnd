<?php

namespace Modules\TimeTable\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\TimeTable\app\Models\TimeTable;

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

        $timeTables = [
            [
                'classroom_id' => 1,
                'section_id' => 1,
                'course_id' => 1,
                'day_of_week' => 'Monday',
                'start_time' => '08:00',
                'end_time' => '09:00',
                'subject_id' => 1,
                'teacher_id' => 7, // John Anderson
                'room_number' => 'I',
                'notes' => 'Mathematics class',
                'status' => 'active',
            ],
            [
                'classroom_id' => 1,
                'section_id' => 1,
                'course_id' => 1,
                'day_of_week' => 'Monday',
                'start_time' => '09:15',
                'end_time' => '10:15',
                'subject_id' => 2,
                'teacher_id' => 8, // Sarah Mitchell
                'room_number' => 'I',
                'notes' => 'English class',
                'status' => 'active',
            ],
            [
                'classroom_id' => 1,
                'section_id' => 1,
                'course_id' => 1,
                'day_of_week' => 'Tuesday',
                'start_time' => '08:00',
                'end_time' => '09:00',
                'subject_id' => 3,
                'teacher_id' => 9, // Robert Thompson
                'room_number' => 'I',
                'notes' => 'Science class',
                'status' => 'active',
            ],
            [
                'classroom_id' => 1,
                'section_id' => 1,
                'course_id' => 1,
                'day_of_week' => 'Wednesday',
                'start_time' => '08:00',
                'end_time' => '09:00',
                'subject_id' => 4,
                'teacher_id' => 10, // Jennifer Garcia
                'room_number' => 'I',
                'notes' => 'History class',
                'status' => 'active',
            ],
            [
                'classroom_id' => 2,
                'section_id' => 3,
                'course_id' => 2,
                'day_of_week' => 'Monday',
                'start_time' => '08:00',
                'end_time' => '09:00',
                'subject_id' => 5,
                'teacher_id' => 11, // Michael Rodriguez
                'room_number' => 'II',
                'notes' => 'Chemistry class',
                'status' => 'active',
            ],
            [
                'classroom_id' => 2,
                'section_id' => 3,
                'course_id' => 2,
                'day_of_week' => 'Wednesday',
                'start_time' => '09:15',
                'end_time' => '10:15',
                'subject_id' => 6,
                'teacher_id' => 12, // Amanda Lee
                'room_number' => 'II',
                'notes' => 'Physics class',
                'status' => 'active',
            ],
        ];

        foreach ($timeTables as $data) {
            TimeTable::create($data);
            $this->command->info("Timetable entry created: {$data['day_of_week']} {$data['start_time']}-{$data['end_time']}");
        }

        $this->command->info(count($timeTables) . ' timetable entries created successfully.');
    }
}
