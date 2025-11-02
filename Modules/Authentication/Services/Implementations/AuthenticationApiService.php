<?php

namespace Modules\Authentication\Services\Implementations;

use Illuminate\Support\Facades\Hash;
use Modules\Authentication\Services\AuthenticationApiServiceInterface;
use Illuminate\Validation\ValidationException;
use Modules\Users\User\App\Models\User;

class AuthenticationApiService implements AuthenticationApiServiceInterface
{
    /**
     * Register a new user and assign role
     */
    public function register(array $data)
    {
        $role = $data['role'] ?? 'student'; // default role if not provided
        $password = $data['password'];

        $data['password'] = Hash::make($password);
        unset($data['role']); // remove role field since it's not in users table

        $user = User::create($data);

        // Assign role using Spatie
        $user->assignRole($role);

        $token = $user->createToken('auth_token')->plainTextToken;

        return ['user' => $user, 'token' => $token];
    }

    /**
     * Login user and return token with role
     */
    public function login(array $credentials)
{
    $user = User::where('email', $credentials['email'])->first();

    if (!$user || !Hash::check($credentials['password'], $user->password)) {
        throw ValidationException::withMessages([
            'email' => ['Invalid credentials provided.']
        ]);
    }

    // Ensure user has at least one role
    if ($user->getRoleNames()->isEmpty()) {
        $user->assignRole('student'); // default role
    }

    $token = $user->createToken('auth_token')->plainTextToken;

    return ['user' => $user, 'token' => $token];
}

    /**
     * Logout user (delete current access token)
     */
    public function logout($user)
    {
        $user->currentAccessToken()->delete();
        return true;
    }
}
