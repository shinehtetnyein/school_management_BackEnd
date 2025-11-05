<?php
// Modules/Subject/database/seeders/SubjectSeeder.php

namespace Modules\Subject\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Subject\App\Models\Subject;

class SubjectSeeder extends Seeder
{
    public function run(): void
    {
        if (app()->environment('production')) {
            $this->command->warn('SubjectSeeder will not run in production.');
            return;
        }

        $this->command->info('Creating Myanmar high school subjects...');

        $subjects = [
            // Primary Level Subjects (Grade 1-5) - Beginner Level
            [
                'subject_code' => 'MM-PRI',
                'subject_name' => 'မြန်မာစာ',
                'subject_desc' => 'မြန်မာဘာသာစကား အခြေခံ',
                'class_level' => 'beginner',
                'status' => 'active'
            ],
            [
                'subject_code' => 'ENG-PRI',
                'subject_name' => 'အင်္ဂလိပ်စာ',
                'subject_desc' => 'အင်္ဂလိပ်ဘာသာစကား အခြေခံ',
                'class_level' => 'beginner',
                'status' => 'active'
            ],
            [
                'subject_code' => 'MATH-PRI',
                'subject_name' => 'သင်္ချာ',
                'subject_desc' => 'အခြေခံ သင်္ချာဘာသာရပ်',
                'class_level' => 'beginner',
                'status' => 'active'
            ],
            [
                'subject_code' => 'SCI-PRI',
                'subject_name' => 'သိပ္ပံ',
                'subject_desc' => 'အခြေခံ သိပ္ပံဘာသာရပ်',
                'class_level' => 'beginner',
                'status' => 'active'
            ],
            [
                'subject_code' => 'SOC-PRI',
                'subject_name' => 'လူမှုရေး',
                'subject_desc' => 'လူမှုရေး သိပ္ပံဘာသာရပ်',
                'class_level' => 'beginner',
                'status' => 'active'
            ],

            // Middle School Subjects (Grade 6-9) - Intermediate Level
            [
                'subject_code' => 'MM-MID',
                'subject_name' => 'မြန်မာစာ',
                'subject_desc' => 'မြန်မာဘာသာစကား အဆင့်မြင့်',
                'class_level' => 'intermediate',
                'status' => 'active'
            ],
            [
                'subject_code' => 'ENG-MID',
                'subject_name' => 'အင်္ဂလိပ်စာ',
                'subject_desc' => 'အင်္ဂလိပ်ဘာသာစကား အဆင့်မြင့်',
                'class_level' => 'intermediate',
                'status' => 'active'
            ],
            [
                'subject_code' => 'MATH-MID',
                'subject_name' => 'သင်္ချာ',
                'subject_desc' => 'အလယ်တန်း သင်္ချာဘာသာရပ်',
                'class_level' => 'intermediate',
                'status' => 'active'
            ],
            [
                'subject_code' => 'PHY-MID',
                'subject_name' => 'ရူပဗေဒ',
                'subject_desc' => 'အခြေခံ ရူပဗေဒဘာသာရပ်',
                'class_level' => 'intermediate',
                'status' => 'active'
            ],
            [
                'subject_code' => 'CHEM-MID',
                'subject_name' => 'ဓာတုဗေဒ',
                'subject_desc' => 'အခြေခံ ဓာတုဗေဒဘာသာရပ်',
                'class_level' => 'intermediate',
                'status' => 'active'
            ],
            [
                'subject_code' => 'BIO-MID',
                'subject_name' => 'ဇီဝဗေဒ',
                'subject_desc' => 'အခြေခံ ဇီဝဗေဒဘာသာရပ်',
                'class_level' => 'intermediate',
                'status' => 'active'
            ],
            [
                'subject_code' => 'HIST-MID',
                'subject_name' => 'သမိုင်း',
                'subject_desc' => 'မြန်မာနှင့် ကမ္ဘာ့သမိုင်း',
                'class_level' => 'intermediate',
                'status' => 'active'
            ],
            [
                'subject_code' => 'GEO-MID',
                'subject_name' => 'ပထဝီဝင်',
                'subject_desc' => 'မြန်မာနှင့် ကမ္ဘာ့ပထဝီဝင်',
                'class_level' => 'intermediate',
                'status' => 'active'
            ],

            // High School Arts Stream Subjects (Grade 10-12) - Advanced Level
            [
                'subject_code' => 'MM-ART',
                'subject_name' => 'မြန်မာစာ',
                'subject_desc' => 'တက္ကသိုလ်ဝင်တန်း မြန်မာစာဘာသာရပ်',
                'class_level' => 'advanced',
                'status' => 'active'
            ],
            [
                'subject_code' => 'ENG-ART',
                'subject_name' => 'အင်္ဂလိပ်စာ',
                'subject_desc' => 'တက္ကသိုလ်ဝင်တန်း အင်္ဂလိပ်စာဘာသာရပ်',
                'class_level' => 'advanced',
                'status' => 'active'
            ],
            [
                'subject_code' => 'ECON-ART',
                'subject_name' => 'စီးပွားရေး',
                'subject_desc' => 'စီးပွားရေးပညာဘာသာရပ်',
                'class_level' => 'advanced',
                'status' => 'active'
            ],
            [
                'subject_code' => 'HIST-ART',
                'subject_name' => 'သမိုင်း',
                'subject_desc' => 'တက္ကသိုလ်ဝင်တန်း သမိုင်းဘာသာရပ်',
                'class_level' => 'advanced',
                'status' => 'active'
            ],
            [
                'subject_code' => 'GEO-ART',
                'subject_name' => 'ပထဝီဝင်',
                'subject_desc' => 'တက္ကသိုလ်ဝင်တန်း ပထဝီဝင်ဘာသာရပ်',
                'class_level' => 'advanced',
                'status' => 'active'
            ],
            [
                'subject_code' => 'PSY-ART',
                'subject_name' => 'စိတ်ပညာ',
                'subject_desc' => 'စိတ်ပညာဘာသာရပ်',
                'class_level' => 'advanced',
                'status' => 'active'
            ],

            // High School Science Stream Subjects (Grade 10-12) - Advanced Level
            [
                'subject_code' => 'MM-SCI',
                'subject_name' => 'မြန်မာစာ',
                'subject_desc' => 'တက္ကသိုလ်ဝင်တန်း မြန်မာစာဘာသာရပ်',
                'class_level' => 'advanced',
                'status' => 'active'
            ],
            [
                'subject_code' => 'ENG-SCI',
                'subject_name' => 'အင်္ဂလိပ်စာ',
                'subject_desc' => 'တက္ကသိုလ်ဝင်တန်း အင်္ဂလိပ်စာဘာသာရပ်',
                'class_level' => 'advanced',
                'status' => 'active'
            ],
            [
                'subject_code' => 'MATH-SCI',
                'subject_name' => 'သင်္ချာ',
                'subject_desc' => 'တက္ကသိုလ်ဝင်တန်း သင်္ချာဘာသာရပ်',
                'class_level' => 'advanced',
                'status' => 'active'
            ],
            [
                'subject_code' => 'PHY-SCI',
                'subject_name' => 'ရူပဗေဒ',
                'subject_desc' => 'တက္ကသိုလ်ဝင်တန်း ရူပဗေဒဘာသာရပ်',
                'class_level' => 'advanced',
                'status' => 'active'
            ],
            [
                'subject_code' => 'CHEM-SCI',
                'subject_name' => 'ဓာတုဗေဒ',
                'subject_desc' => 'တက္ကသိုလ်ဝင်တန်း ဓာတုဗေဒဘာသာရပ်',
                'class_level' => 'advanced',
                'status' => 'active'
            ],
            [
                'subject_code' => 'BIO-SCI',
                'subject_name' => 'ဇီဝဗေဒ',
                'subject_desc' => 'တက္ကသိုလ်ဝင်တန်း ဇီဝဗေဒဘာသာရပ်',
                'class_level' => 'advanced',
                'status' => 'active'
            ]
        ];

        foreach ($subjects as $subjectData) {
            Subject::firstOrCreate(
                ['subject_code' => $subjectData['subject_code']],
                $subjectData
            );
        }

        $this->command->info('Myanmar high school subjects created successfully.');
        $this->command->info('Total subjects: ' . count($subjects));
        $this->command->info('Primary Level: 5 subjects');
        $this->command->info('Middle School: 8 subjects');
        $this->command->info('Arts Stream: 6 subjects');
        $this->command->info('Science Stream: 6 subjects');
    }
}
