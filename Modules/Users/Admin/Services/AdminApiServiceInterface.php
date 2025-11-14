<?php
// Modules/Users/Admin/Services/AdminApiServiceInterface.php

namespace Modules\Users\Admin\Services;

use Modules\Users\User\App\Models\User;

interface AdminApiServiceInterface
{
    /**
     * Get all users with pagination
     */
    public function index(int $perPage = 10): array;

    /**
     * Get single user by ID
     */
    public function show(int $id): ?User;

    /**
     * Create a new user
     */
    public function store(array $data): User;

    /**
     * Update user
     */
    public function update(int $id, array $data): User;

    /**
     * Delete user
     */
    public function destroy(int $id): bool;

    /**
     * Assign role to user
     */
    public function assignRole(int $userId, string $roleName): User;

    /**
     * Remove role from user
     */
    public function removeRole(int $userId, string $roleName): User;

    /**
     * Assign permission to user
     */
    public function grantPermission(int $userId, string $permissionName): User;

    /**
     * Remove permission from user
     */
    public function revokePermission(int $userId, string $permissionName): User;

    /**
     * Get users by role
     */
    public function getUsersByRole(string $roleName): array;
}
