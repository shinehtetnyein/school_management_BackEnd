<?php

namespace Modules\Authentication\Services\Implementations;

use App\Console\Enums\Role;
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
        $roleSlug = $data['role'] ?? Role::STUDENT->value; // default role slug
        $password = $data['password'];

        $data['password'] = Hash::make($password);
        unset($data['role']); // remove role field since it's not in users table

        $user = User::create($data);

        // Validate slug and get DB label via enum
        $roleEnum = Role::tryFrom($roleSlug);
        $dbRole = $roleEnum ? $roleEnum->label() : $roleSlug;

        // Assign role using Spatie
        $user->assignRole($dbRole);

        // Determine token abilities from config
        $abilities = config('role_abilities.' . $roleSlug, ['*']);

        $token = $user->createToken('auth_token', $abilities)->plainTextToken;

        return ['user' => $user, 'token' => $token, 'role' => $roleSlug, 'abilities' => $abilities];
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

    // Requested role
    $requestedRole = $credentials['role'] ?? null;

    if (!$requestedRole) {
        throw ValidationException::withMessages([
            'role' => ['Role is required for login.']
        ]);
    }

    // Validate requested role using enum
    $roleEnum = Role::tryFrom($requestedRole);
    if (! $roleEnum) {
        throw ValidationException::withMessages([
            'role' => ['The selected role is invalid.']
        ]);
    }

    $dbRoleName = $roleEnum->label();

    if (! $user->hasRole($dbRoleName)) {
        throw ValidationException::withMessages([
            'role' => ['You are not authorized for this role.']
        ]);
    }

    // Token abilities
    $abilities = config('role_abilities.' . $requestedRole, ['*']);

    $token = $user->createToken('auth_token', $abilities)->plainTextToken;

    return [
        'user' => $user,
        'roles' => $user->getRoleNames(),
        'token' => $token,
        'role' => $requestedRole,
        'abilities' => $abilities,
    ];
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
