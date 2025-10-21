<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DevUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Safety: do not run this seeder in production
        if (app()->environment('production')) {
            $this->command->warn('DevUsersSeeder will not run in production.');
            return;
        }

        // Ask for confirmation
        if (! $this->command->confirm('This will create development users (Parent, Librarian, Teacher, Student, Admin, Root Admin). Continue?', true)) {
            $this->command->warn('Dev user seeding cancelled.');
            return;
        }
        $this->command->info('Creating development users: Root Admin, Admin, Parent, Librarian, Teacher, Student');

        // Parent user
        $parent = User::firstOrCreate(
            ['email' => 'parent@example.com'],
            [
                'name' => 'Parent User',
                'password' => Hash::make('password'),
            ]
        );
        $parent->assignRole('Parent');

        // Librarian user
        $librarian = User::firstOrCreate(
            ['email' => 'librarian@example.com'],
            [
                'name' => 'Librarian User',
                'password' => Hash::make('password'),
            ]
        );
        $librarian->assignRole('Librarian');

        // Teacher user
        $teacher = User::firstOrCreate(
            ['email' => 'teacher@example.com'],
            [
                'name' => 'Teacher User',
                'password' => Hash::make('password'),
            ]
        );
        $teacher->assignRole('Teacher');

        // Student user
        $student = User::firstOrCreate(
            ['email' => 'student@example.com'],
            [
                'name' => 'Student User',
                'password' => Hash::make('password'),
            ]
        );
        $student->assignRole('Student');

        // Admin user
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'),
            ]
        );
        $admin->assignRole('Admin');

        // Root Admin user
        $root = User::firstOrCreate(
            ['email' => 'root@example.com'],
            [
                'name' => 'Root Admin',
                'password' => Hash::make('password'),
            ]
        );
        $root->assignRole('Root Admin');

        $this->command->info('Dev users created (password for all is "password").');
    }
}
