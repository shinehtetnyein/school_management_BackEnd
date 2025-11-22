<?php

namespace Modules\ClassRoom\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\ClassRoom\app\Models\Classroom;
use Modules\ClassRoom\app\Models\Section;

class SectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create sections Section A..Section E for every existing classroom using Eloquent
        $classroomIds = Classroom::pluck('id')->all();

        $letters = ['A', 'B', 'C', 'D', 'E'];

        foreach ($classroomIds as $cid) {
            foreach ($letters as $letter) {
                Section::create([
                    'name' => 'Section ' . $letter,
                    'classroom_id' => $cid,
                    'status' => 'active',
                ]);
            }
        }
    }
}
