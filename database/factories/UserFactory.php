<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    /**
     * State: parent user
     */
    public function parent(): static
    {
        return $this->state(fn (array $attributes) => [
            'email' => 'parent@example.com',
            'name' => 'Parent User',
        ]);
    }

    /**
     * State: librarian user
     */
    public function librarian(): static
    {
        return $this->state(fn (array $attributes) => [
            'email' => 'librarian@example.com',
            'name' => 'Librarian User',
        ]);
    }

    /**
     * State: admin user
     */
    public function admin(): static
    {
        return $this->state(fn (array $attributes) => [
            'email' => 'admin@example.com',
            'name' => 'Admin User',
        ]);
    }

    /**
     * State: root admin user
     */
    public function rootAdmin(): static
    {
        return $this->state(fn (array $attributes) => [
            'email' => 'root@example.com',
            'name' => 'Root Admin',
        ]);
    }
}
