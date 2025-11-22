<?php

namespace Modules\ClassRoom\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\ClassRoom\app\Models\Classroom;

class ClassroomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create classrooms with Roman numerals I..XII
        $romans = ['I','II','III','IV','V','VI','VII','VIII','IX','X','XI','XII'];
        foreach ($romans as $roomNumber) {
            $data = [
                'room_number' => $roomNumber,
                'building' => 'Main',
                'room_type' => 'standard',
            ];

            $existing = Classroom::where('room_number', $roomNumber)->first();
            if ($existing) {
                $this->command->info("Classroom {$roomNumber} already exists, skipping.");
                continue;
            }

            $room = Classroom::create($data);
            $this->command->info("Created classroom: {$room->room_number}");
        }
    }
}

