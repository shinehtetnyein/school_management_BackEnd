<?php

namespace Database\Seeders;

use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Database\Seeders\DevUsersSeeder;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Modules\AcademicYears\Database\Seeders\AcademicYearSeeder;
use Modules\Course\Database\Seeders\CourseSeeder;
use Modules\Course\Database\Seeders\EnrollmentSeeder;
use Modules\Subject\Database\Seeders\CourseSubjectSeeder;
use Modules\Subject\Database\Seeders\SubjectSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed roles and permissions first
        $this->call(RolesAndPermissionsSeeder::class);

        // Create a test user
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        // Create development users (parent, librarian, teacher, student)
        $this->call([
            DevUsersSeeder::class,
            AcademicYearSeeder::class,
            CourseSeeder::class,
            EnrollmentSeeder::class,
            SubjectSeeder::class,
            CourseSubjectSeeder::class,
        ]);
    }
}
