<?php

namespace Modules\Users\User\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Users\User\App\Http\Requests\StoreUserApiRequest;
use Modules\Users\User\App\Http\Requests\UpdateUserApiRequest;
use Modules\Users\User\App\Http\Resources\UserApiResource;
use Modules\Users\User\Services\UserApiServiceInterface;

class UserApiController extends Controller
{
    public function __construct(protected UserApiServiceInterface $userApiService) {}

    /**
     * Display a listing of users
     */
    public function index(Request $request)
    {
        [$limit, $offset] = getLimitOffsetFromRequest($request);
        [$noPagination, $pagPerPage] = getNoPaginationPagPerPageFromRequest($request);

        $users = $this->userApiService->getAll(
            $limit,
            $offset,
            $noPagination,
            $pagPerPage
        );

        $data = [
            'users' => ($noPagination || $pagPerPage)
                ? UserApiResource::collection($users)
                : UserApiResource::collection($users)->response()->getData(true),
        ];

        return apiResponse(true, 'Data retrieved successfully', $data);
    }

    /**
     * Store a newly created user
     */
    public function store(StoreUserApiRequest $request)
    {
        $validatedData = $request->validated();
        $user = $this->userApiService->create($validatedData);

        return apiResponse(true, 'User created successfully', [
            'user' => new UserApiResource($user),
        ]);
    }

    /**
     * Display the specified user
     */
    public function show(string $id)
    {
        $user = $this->userApiService->get($id);

        return apiResponse(true, 'Data retrieved successfully', [
            'user' => new UserApiResource($user),
        ]);
    }

    /**
     * Update the specified user
     */
    public function update(UpdateUserApiRequest $request, string $id)
    {
        $validatedData = $request->validated();
        $user = $this->userApiService->update($id, $validatedData);

        return apiResponse(true, 'User updated successfully', [
            'user' => new UserApiResource($user),
        ]);
    }

    /**
     * Remove the specified user
     */
    public function destroy(string $id)
    {
        $deletedName = $this->userApiService->delete($id);

        return apiResponse(true, 'User deleted successfully', [
            'name' => $deletedName,
        ]);
    }
}
