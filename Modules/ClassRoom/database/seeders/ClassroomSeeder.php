<?php

namespace Modules\ClassRoom\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClassroomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $classrooms = [
            [
                'room_number' => 'A101',
                'building' => 'Building A',
                'room_type' => 'Classroom',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'room_number' => 'A102',
                'building' => 'Building A',
                'room_type' => 'Classroom',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'room_number' => 'B101',
                'building' => 'Building B',
                'room_type' => 'Classroom',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'room_number' => 'B102',
                'building' => 'Building B',
                'room_type' => 'Classroom',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'room_number' => 'LAB01',
                'building' => 'Building C',
                'room_type' => 'Lab',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('classrooms')->insert($classrooms);
    }
}
