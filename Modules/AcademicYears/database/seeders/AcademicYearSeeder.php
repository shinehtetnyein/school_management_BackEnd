<?php
// Modules/Academic/database/seeders/AcademicYearSeeder.php

namespace Modules\AcademicYears\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\AcademicYears\App\Models\AcademicYear;
use Modules\Users\User\App\Models\User;

class AcademicYearSeeder extends Seeder
{
    public function run(): void
    {
        // Only run in non-production environments
        if (app()->environment('production')) {
            $this->command->warn('AcademicYearSeeder will not run in production.');
            return;
        }

        if (!$this->command->confirm('This will create academic years. Continue?', true)) {
            $this->command->warn('Academic year seeding cancelled.');
            return;
        }

        $this->command->info('Creating academic years...');

        // Get an admin user for created_by/updated_by fields
        $adminUser = User::where('email', 'admin@gmail.com')->first();

        if (!$adminUser) {
            $this->command->error('Admin user not found. Please run DevUsersSeeder first.');
            return;
        }

        $academicYears = [
            [
                'year_name' => '2024-2025',
                'start_date' => '2024-09-01 00:00:00',
                'end_date' => '2025-06-30 23:59:59',
                'is_current' => true,
                'status' => 'active',
                'description' => 'Academic Year 2024-2025',
                'created_by' => $adminUser->id,
                'updated_by' => $adminUser->id,
            ],
            [
                'year_name' => '2023-2024',
                'start_date' => '2023-09-01 00:00:00',
                'end_date' => '2024-06-30 23:59:59',
                'is_current' => false,
                'status' => 'completed',
                'description' => 'Academic Year 2023-2024',
                'created_by' => $adminUser->id,
                'updated_by' => $adminUser->id,
            ],
            [
                'year_name' => '2025-2026',
                'start_date' => '2025-09-01 00:00:00',
                'end_date' => '2026-06-30 23:59:59',
                'is_current' => false,
                'status' => 'inactive',
                'description' => 'Academic Year 2025-2026 (Upcoming)',
                'created_by' => $adminUser->id,
                'updated_by' => $adminUser->id,
            ]
        ];

        foreach ($academicYears as $academicYearData) {
            AcademicYear::firstOrCreate(
                ['year_name' => $academicYearData['year_name']],
                $academicYearData
            );
        }

        $this->command->info('Academic years created successfully.');
        $this->command->info('Current academic year: 2024-2025');
    }
}
