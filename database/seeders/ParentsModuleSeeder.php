<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Modules\Users\User\App\Models\User;
use App\Console\Enums\Role;

class ParentsModuleSeeder extends Seeder
{
    public function run(): void
    {
        if (app()->environment('production')) {
            $this->command->warn('ParentsModuleSeeder will not run in production.');
            return;
        }

        $parents = [
            [
                'first_name' => 'Olivia',
                'last_name' => 'Martin',
                'email' => 'olivia.martin@school.com',
                'phone_no' => '0956789012',
                'address' => '12 Parent Lane',
                'city' => 'Yangon',
                'country' => 'Myanmar',
                'gender' => 'female',
                'status' => 'active',
                'date_of_birth' => '1978-02-10',
            ],
            [
                'first_name' => 'William',
                'last_name' => 'Nguyen',
                'email' => 'william.nguyen@school.com',
                'phone_no' => '0957890123',
                'address' => '34 Guardian Road',
                'city' => 'Mandalay',
                'country' => 'Myanmar',
                'gender' => 'male',
                'status' => 'active',
                'date_of_birth' => '1975-11-05',
            ],
            [
                'first_name' => 'Sophia',
                'last_name' => 'Khin',
                'email' => 'sophia.khin@school.com',
                'phone_no' => '0958901234',
                'address' => '56 Family Court',
                'city' => 'Naypyidaw',
                'country' => 'Myanmar',
                'gender' => 'female',
                'status' => 'active',
                'date_of_birth' => '1980-09-22',
            ],
        ];

        foreach ($parents as $data) {
            $existing = User::where('email', $data['email'])->first();
            if ($existing) {
                $this->command->info("Parent already exists: {$data['email']}");
                continue;
            }

            $user = User::create([
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'name' => $data['first_name'] . ' ' . $data['last_name'],
                'email' => $data['email'],
                'phone_no' => $data['phone_no'] ?? null,
                'address' => $data['address'] ?? null,
                'city' => $data['city'] ?? null,
                'country' => $data['country'] ?? null,
                'gender' => $data['gender'] ?? null,
                'status' => $data['status'] ?? 'active',
                'date_of_birth' => $data['date_of_birth'] ?? null,
                'password' => Hash::make('password'),
                'uuid' => \Illuminate\Support\Str::uuid(),
            ]);

            $user->assignRole(Role::PARENT->value);

            $this->command->info("Parent created: {$user->first_name} {$user->last_name}");
        }

        $this->command->info(count($parents) . ' default parents created by ParentsModuleSeeder.');
    }
}
