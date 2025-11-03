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

    // Requested role
    $requestedRole = $credentials['role'] ?? null;

    if (!$requestedRole) {
        throw ValidationException::withMessages([
            'role' => ['Role is required for login.']
        ]);
    }

    // Map simple enum/slug to DB role name
    $roleMap = [
        'root_admin' => 'Root Admin',
        'admin'      => 'Admin',
        'teacher'    => 'Teacher',
        'student'    => 'Student',
        'parent'     => 'Parent',
        'librarian'  => 'Librarian',
        'guest'      => 'Guest',
        'accountant' => 'Accountant',
    ];

    if (!array_key_exists($requestedRole, $roleMap)) {
        throw ValidationException::withMessages([
            'role' => ['The selected role is invalid.']
        ]);
    }

    $dbRoleName = $roleMap[$requestedRole];

    if (!$user->hasRole($dbRoleName)) {
        throw ValidationException::withMessages([
            'role' => ['You are not authorized for this role.']
        ]);
    }

    $token = $user->createToken('auth_token')->plainTextToken;

    return [
        'user' => $user,
        'roles' => $user->getRoleNames(),
        'token' => $token,
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
