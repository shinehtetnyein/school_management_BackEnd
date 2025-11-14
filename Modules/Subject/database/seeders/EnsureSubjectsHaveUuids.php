<?php

namespace Modules\Subject\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Subject\App\Models\Subject;
use Illuminate\Support\Str;

class EnsureSubjectsHaveUuids extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $subjectsWithoutUuid = Subject::whereNull('uuid')->get();

        if ($subjectsWithoutUuid->isEmpty()) {
            echo "\n✓ All subjects already have UUIDs.\n";
            return;
        }

        echo sprintf("\nGenerating UUIDs for %d subjects...\n", $subjectsWithoutUuid->count());

        foreach ($subjectsWithoutUuid as $subject) {
            $subject->update(['uuid' => (string) Str::uuid()]);
            echo "✓ Subject ID {$subject->id} ({$subject->subject_name}) - UUID: {$subject->uuid}\n";
        }

        echo sprintf("\n✓ Successfully generated UUIDs for %d subjects!\n", $subjectsWithoutUuid->count());
    }
}
