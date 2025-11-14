<?php

namespace Modules\Authentication\App\Http\Controllers;

use App\Console\Enums\Role;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Modules\Authentication\Services\AuthenticationApiServiceInterface;
use Modules\Users\User\App\Http\Resources\UserApiResource;

use function Laravel\Prompts\error;

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
        'token' => $result['token'],
        'role' => $result['role'] ?? null,
        'abilities' => $result['abilities'] ?? null,
    ]);
}

public function login(Request $request)
{
    $validated = $request->validate([
        'email'    => 'required|email',
        'password' => 'required|string',
        'role'     => ['required'],
    ]);

    try {
        $result = $this->authService->login($validated);

        return apiResponse(true, 'Login successful', [
            'user'  => new UserApiResource($result['user']),
            'token' => $result['token'],
            'role' => $result['role'] ?? null,
            'abilities' => $result['abilities'] ?? null,
        ]);

    } catch (ValidationException $e) {
        return apiResponse(false, 'Login failed', null, 403, $e->errors());
    } catch (\Exception $e) {
        return apiResponse(false, 'Something went wrong', null, 500, [
            'exception' => [$e->getMessage()]
        ]);
    }
}


    /**
     * Logout current user (delete current token)
     */
    public function logout(Request $request)
    {
        try {
            $user = $request->user();

            if (!$user) {
                return apiResponse(false, 'Unauthenticated.', null, 401);
            }

            $this->authService->logout($user);

            return apiResponse(true, 'Logged out successfully.');
        } catch (\Exception $e) {
            return apiResponse(false, 'Failed to logout.', null, 500, [$e->getMessage()]);
        }
    }

}
