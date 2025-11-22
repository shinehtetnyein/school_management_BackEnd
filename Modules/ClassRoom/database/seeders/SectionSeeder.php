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
        // Ensure sections Section A..Section E exist for every existing classroom (idempotent)
        $classroomIds = Classroom::pluck('id')->all();

        $letters = ['A', 'B', 'C', 'D', 'E'];

        foreach ($classroomIds as $cid) {
            foreach ($letters as $letter) {
                $name = 'Section ' . $letter;
                $exists = Section::where('classroom_id', $cid)->where('name', $name)->exists();
                if ($exists) {
                    continue;
                }

                Section::create([
                    'name' => $name,
                    'classroom_id' => $cid,
                    'status' => 'active',
                ]);
            }
        }
    }
}
