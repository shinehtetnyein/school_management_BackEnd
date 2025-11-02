<?php

namespace Modules\Authentication\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Authentication\Services\AuthenticationApiServiceInterface;
use Modules\Users\User\App\Http\Resources\UserApiResource;

class AuthenticationApiController extends Controller
{
   public function __construct(protected AuthenticationApiServiceInterface $authService) {}

   public function register(Request $request)
{
    $validated = $request->validate([
        'name' => 'required|string|max:100',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|string|min:6|confirmed',
        'role' => 'required|in:student,teacher,admin',
    ]);

    $result = $this->authService->register($validated);

    return apiResponse(true, 'Registration successful', [
        'user' => new UserApiResource($result['user']),
        'token' => $result['token']
    ]);
}

public function login(Request $request)
{
    $validated = $request->validate([
        'email' => 'required|email',
        'password' => 'required|string',
        'role' => 'required|in:student,teacher,admin',
    ]);

    $result = $this->authService->login($validated);

    return apiResponse(true, 'Login successful', [
        'user' => new UserApiResource($result['user']),
        'token' => $result['token']
    ]);
}

}
