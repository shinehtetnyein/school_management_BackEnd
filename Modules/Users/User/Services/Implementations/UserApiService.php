<?php

namespace Modules\Users\User\Services\Implementations;

use Illuminate\Support\Facades\Hash;
use Modules\Users\User\App\Models\User;
use Modules\Users\User\Services\UserApiServiceInterface;

class UserApiService implements UserApiServiceInterface
{
    /**
     * Retrieve a single user by ID
     */
    public function get($id)
    {
        return User::findOrFail($id);
    }

    /**
     * Retrieve all users with optional pagination
     */
    public function getAll(
        ?int $limit = null,
        ?int $offset = null,
        ?bool $noPagination = false,
        ?int $pagPerPage = null
    ) {
        $query = User::orderBy('id', 'desc');

        if ($noPagination) {
            return $query->get();
        }

        if ($pagPerPage) {
            return $query->paginate($pagPerPage);
        }

        if ($limit !== null && $offset !== null) {
            return $query->skip($offset)->take($limit)->get();
        }

        return $query->get();
    }

    /**
     * Create a new user
     */
    public function create(array $userData)
    {
        if (!empty($userData['password'])) {
            $userData['password'] = Hash::make($userData['password']);
        }

        return User::create($userData);
    }

    /**
     * Update an existing user by ID
     */
    public function update($id, array $userData)
    {
        $user = User::findOrFail($id);

        if (!empty($userData['password'])) {
            $userData['password'] = Hash::make($userData['password']);
        }

        $user->update($userData);

        return $user;
    }

    /**
     * Delete a user by ID
     */
    public function delete($id)
    {
        $user = User::findOrFail($id);
        $userName = $user->name ?? "User #{$id}";
        $user->delete();

        return $userName;
    }
}
