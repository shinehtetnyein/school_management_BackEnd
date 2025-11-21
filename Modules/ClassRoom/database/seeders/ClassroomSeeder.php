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
                'room_number' => 'I',
                'building' => 'Building A',
                'room_type' => 'Classroom',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'room_number' => 'II',
                'building' => 'Building A',
                'room_type' => 'Classroom',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'room_number' => 'III',
                'building' => 'Building B',
                'room_type' => 'Classroom',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'room_number' => 'IV',
                'building' => 'Building B',
                'room_type' => 'Classroom',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'room_number' => 'V',
                'building' => 'Building C',
                'room_type' => 'Classroom',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('classrooms')->insert($classrooms);
    }
}
