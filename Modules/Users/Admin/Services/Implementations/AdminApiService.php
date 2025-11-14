<?php
// Modules/Users/Admin/Services/Implementations/AdminApiService.php

namespace Modules\Users\Admin\Services\Implementations;

use Modules\Users\User\App\Models\User;
use Modules\Users\Admin\Services\AdminApiServiceInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminApiService implements AdminApiServiceInterface
{
    public function index(int $perPage = 10): array
    {
        $admins = User::with('roles', 'permissions')
            ->orderBy('id', 'asc')
            ->get()
            ->map(function($admin) {
                return [
                    'id' => $admin->id,
                    'uuid' => $admin->uuid,
                    'name' => $admin->name,
                    'email' => $admin->email,
                ];
            })->toArray();

        return [
            'total_count' => count($admins),
            'admins' => $admins
        ];
    }

    public function show(int $id): ?User
    {
        return User::with('roles', 'permissions')->find($id);
    }

    public function store(array $data): User
    {
        return DB::transaction(function () use ($data) {
            // Hash password if provided
            if (isset($data['password'])) {
                $data['password'] = Hash::make($data['password']);
            }

            // Add UUID
            $data['uuid'] = \Illuminate\Support\Str::uuid();

            $user = User::create($data);

            // Assign default role if provided
            if (isset($data['role'])) {
                $user->assignRole($data['role']);
            }

            return $user;
        });
    }

    public function update(int $id, array $data): User
    {
        return DB::transaction(function () use ($id, $data) {
            $user = User::findOrFail($id);

            // Hash password if provided
            if (isset($data['password'])) {
                $data['password'] = Hash::make($data['password']);
            } else {
                unset($data['password']);
            }

            $user->update($data);

            return $user->fresh('roles', 'permissions');
        });
    }

    public function destroy(int $id): bool
    {
        $user = User::findOrFail($id);

        // Remove all roles and permissions
        $user->roles()->detach();
        $user->permissions()->detach();

        return $user->delete();
    }

    public function assignRole(int $userId, string $roleName): User
    {
        $user = User::findOrFail($userId);
        $user->assignRole($roleName);

        return $user->fresh('roles', 'permissions');
    }

    public function removeRole(int $userId, string $roleName): User
    {
        $user = User::findOrFail($userId);
        $user->removeRole($roleName);

        return $user->fresh('roles', 'permissions');
    }

    public function grantPermission(int $userId, string $permissionName): User
    {
        $user = User::findOrFail($userId);
        $user->givePermissionTo($permissionName);

        return $user->fresh('roles', 'permissions');
    }

    public function revokePermission(int $userId, string $permissionName): User
    {
        $user = User::findOrFail($userId);
        $user->revokePermissionTo($permissionName);

        return $user->fresh('roles', 'permissions');
    }

    public function getUsersByRole(string $roleName): array
    {
        return User::role($roleName)
            ->with('roles', 'permissions')
            ->orderBy('id', 'asc')
            ->get()
            ->toArray();
    }
}
