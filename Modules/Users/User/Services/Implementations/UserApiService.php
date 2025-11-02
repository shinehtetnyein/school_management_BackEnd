<?php

namespace Modules\Users\User\Services\Implementations;

use Illuminate\Support\Facades\Hash;
use Modules\Users\User\app\Models\User;
use Modules\Users\User\Services\UserApiServiceInterface;

class UserApiService implements UserApiServiceInterface
{
    /**
     * Retrieve a single user by ID with optional relations
     */
    public function get($id, array $relations = [])
    {
        return User::with($relations)->findOrFail($id);
    }

    /**
     * Retrieve all users with optional pagination and relations
     */
    public function getAll(
        array $relations = [],
        ?int $limit = null,
        ?int $offset = null,
        ?bool $noPagination = false,
        ?int $pagPerPage = null
    ) {
        $query = User::with($relations)->orderBy('id', 'desc');

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
        $user->delete();

        return $user->name ?? "User #{$id}";
    }
}
