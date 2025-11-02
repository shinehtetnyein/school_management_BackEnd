<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Users\User\App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role as SpatieRole;
use App\Console\Enums\Role as RoleEnum;

class DevUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (app()->environment('production')) {
            $this->command->warn('DevUsersSeeder will not run in production.');
            return;
        }

        if (!$this->command->confirm('This will create development users and roles. Continue?', true)) {
            $this->command->warn('Dev user seeding cancelled.');
            return;
        }

        $this->command->info('Creating roles with guard "sanctum"...');

        // Ensure all roles exist with the correct guard
        foreach (RoleEnum::cases() as $roleEnum) {
            $role = SpatieRole::firstOrCreate(
                ['name' => $roleEnum->label()],
                ['guard_name' => 'sanctum']
            );

            // Fix any roles that may already exist with wrong guard
            if ($role->guard_name !== 'sanctum') {
                $role->guard_name = 'sanctum';
                $role->save();
            }
        }

        $this->command->info('Creating users and assigning roles...');

        // Users to create
        $users = [
            'parent@gmail.com' => RoleEnum::PARENT,
            'librarian@gmail.com' => RoleEnum::LIBRARIAN,
            'teacher@gmail.com' => RoleEnum::TEACHER,
            'student@gmail.com' => RoleEnum::STUDENT,
            'admin@gmail.com' => RoleEnum::ADMIN,
            'root@gmail.com' => RoleEnum::ROOT_ADMIN,
        ];

        foreach ($users as $email => $roleEnum) {
            $user = User::firstOrCreate(
                ['email' => $email],
                [
                    'name' => $roleEnum->label() . ' User',
                    'password' => Hash::make('password'),
                ]
            );

            // Assign role only if not already assigned
            if (!$user->hasRole($roleEnum->label(), 'sanctum')) {
                $user->assignRole($roleEnum->label());
            }
        }

        $this->command->info('Dev users and roles created successfully (password for all: "password").');
    }
}
