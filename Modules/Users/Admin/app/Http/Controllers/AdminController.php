<?php
// Modules/Users/Admin/app/Http/Controllers/AdminController.php

namespace Modules\Users\Admin\App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Modules\Users\Admin\Services\AdminApiServiceInterface;
use Modules\Users\Admin\App\Http\Resource\AdminUserResource;
use Modules\Users\Admin\App\Http\Request\StoreUserRequest;
use Modules\Users\Admin\App\Http\Request\UpdateUserRequest;
use Modules\Users\Admin\App\Http\Request\AssignRoleRequest;
use Modules\Users\Admin\App\Http\Request\AssignPermissionRequest;

class AdminController extends Controller
{
    protected $adminService;

    public function __construct(AdminApiServiceInterface $adminService)
    {
        $this->adminService = $adminService;
    }

    /**
     * Get all users with pagination
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $perPage = $request->query('per_page', 10);
            $users = $this->adminService->index((int)$perPage);

            return \apiResponse(
                true,
                'Users retrieved successfully.',
                AdminUserResource::collection($users)
            );
        } catch (\Exception $e) {
            return \apiResponse(
                false,
                'Failed to retrieve users.',
                null,
                500,
                [$e->getMessage()]
            );
        }
    }

    /**
     * Get single user
     */
    public function show(int $id): JsonResponse
    {
        try {
            $user = $this->adminService->show($id);

            if (!$user) {
                return \apiResponse(
                    false,
                    'User not found.',
                    null,
                    404
                );
            }

            return \apiResponse(
                true,
                'User retrieved successfully.',
                new AdminUserResource($user)
            );
        } catch (\Exception $e) {
            return \apiResponse(
                false,
                'Failed to retrieve user.',
                null,
                500,
                [$e->getMessage()]
            );
        }
    }

    /**
     * Create new user
     */
    public function store(StoreUserRequest $request): JsonResponse
    {
        try {
            $validated = $request->validated();
            $user = $this->adminService->store($validated);

            return \apiResponse(
                true,
                'User created successfully.',
                new AdminUserResource($user),
                201
            );
        } catch (\Exception $e) {
            return \apiResponse(
                false,
                'Failed to create user.',
                null,
                500,
                [$e->getMessage()]
            );
        }
    }

    /**
     * Update user
     */
    public function update(UpdateUserRequest $request, int $id): JsonResponse
    {
        try {
            $validated = $request->validated();
            $user = $this->adminService->update($id, $validated);

            return \apiResponse(
                true,
                'User updated successfully.',
                new AdminUserResource($user)
            );
        } catch (\Exception $e) {
            return \apiResponse(
                false,
                'Failed to update user.',
                null,
                500,
                [$e->getMessage()]
            );
        }
    }

    /**
     * Delete user
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $result = $this->adminService->destroy($id);

            if ($result) {
                return \apiResponse(
                    true,
                    'User deleted successfully.'
                );
            }

            return \apiResponse(
                false,
                'Failed to delete user.',
                null,
                500
            );
        } catch (\Exception $e) {
            return \apiResponse(
                false,
                'Failed to delete user.',
                null,
                500,
                [$e->getMessage()]
            );
        }
    }

    /**
     * Assign role to user
     */
    public function assignRole(AssignRoleRequest $request, int $id): JsonResponse
    {
        try {
            $validated = $request->validated();
            $user = $this->adminService->assignRole($id, $validated['role']);

            return \apiResponse(
                true,
                'Role assigned successfully.',
                new AdminUserResource($user)
            );
        } catch (\Exception $e) {
            return \apiResponse(
                false,
                'Failed to assign role.',
                null,
                500,
                [$e->getMessage()]
            );
        }
    }

    /**
     * Remove role from user
     */
    public function removeRole(Request $request, int $id): JsonResponse
    {
        try {
            $validated = $request->validate([
                'role' => 'required|string|exists:roles,name'
            ]);

            $user = $this->adminService->removeRole($id, $validated['role']);

            return \apiResponse(
                true,
                'Role removed successfully.',
                new AdminUserResource($user)
            );
        } catch (\Exception $e) {
            return \apiResponse(
                false,
                'Failed to remove role.',
                null,
                500,
                [$e->getMessage()]
            );
        }
    }

    /**
     * Grant permission to user
     */
    public function grantPermission(AssignPermissionRequest $request, int $id): JsonResponse
    {
        try {
            $validated = $request->validated();
            $user = $this->adminService->grantPermission($id, $validated['permission']);

            return \apiResponse(
                true,
                'Permission granted successfully.',
                new AdminUserResource($user)
            );
        } catch (\Exception $e) {
            return \apiResponse(
                false,
                'Failed to grant permission.',
                null,
                500,
                [$e->getMessage()]
            );
        }
    }

    /**
     * Revoke permission from user
     */
    public function revokePermission(Request $request, int $id): JsonResponse
    {
        try {
            $validated = $request->validate([
                'permission' => 'required|string|exists:permissions,name'
            ]);

            $user = $this->adminService->revokePermission($id, $validated['permission']);

            return \apiResponse(
                true,
                'Permission revoked successfully.',
                new AdminUserResource($user)
            );
        } catch (\Exception $e) {
            return \apiResponse(
                false,
                'Failed to revoke permission.',
                null,
                500,
                [$e->getMessage()]
            );
        }
    }

    /**
     * Get users by role
     */
    public function getUsersByRole(string $roleName): JsonResponse
    {
        try {
            $users = $this->adminService->getUsersByRole($roleName);

            return \apiResponse(
                true,
                "Users with role '{$roleName}' retrieved successfully.",
                AdminUserResource::collection(collect($users))
            );
        } catch (\Exception $e) {
            return \apiResponse(
                false,
                'Failed to retrieve users by role.',
                null,
                500,
                [$e->getMessage()]
            );
        }
    }
}
