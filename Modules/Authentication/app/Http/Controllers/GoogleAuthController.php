<?php

namespace Modules\Authentication\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Users\User\App\Models\User;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;
use GuzzleHttp\Client as GuzzleHttpClient;

class GoogleAuthController extends Controller
{
    /**
     * Redirect user to Google for authentication
     */
    public function redirect()
    {
        return Socialite::driver('google')->stateless()->redirect();
    }

    /**
     * Handle Google callback and authenticate user
     */
    public function callback(Request $request)
    {
        try {
            // Disable SSL verification for local development
            $guzzleClient = new \GuzzleHttp\Client([
                'verify' => env('APP_ENV') === 'production' ? true : false,
            ]);
            
            $googleUser = Socialite::driver('google')
                ->setHttpClient($guzzleClient)
                ->stateless()
                ->user();

            // Find or create user
            $user = User::updateOrCreate(
                ['email' => $googleUser->getEmail()],
                [
                    'name' => $googleUser->getName(),
                    'first_name' => $googleUser->user['given_name'] ?? null,
                    'last_name' => $googleUser->user['family_name'] ?? null,
                    'profile_photo' => $googleUser->getAvatar(),
                    'uuid' => Str::uuid(),
                    'password' => bcrypt(Str::random(24)),
                ]
            );

            // Generate token
            $token = $user->createToken('api-token')->plainTextToken;

            // Prepare user data for frontend
            $userData = json_encode([
                'id' => $user->id,
                'uuid' => $user->uuid,
                'name' => $user->name,
                'email' => $user->email,
                'profile_photo' => $user->profile_photo,
            ]);

            // Get frontend URL from environment
            $frontendUrl = env('FRONTEND_URL', 'http://localhost:5174');

            // Redirect to frontend callback with token and user data
            return redirect(
                $frontendUrl . '/google/callback?' .
                'token=' . urlencode($token) .
                '&user=' . urlencode($userData)
            );

        } catch (\Exception $e) {
            // Get frontend URL from environment
            $frontendUrl = env('FRONTEND_URL', 'http://localhost:5174');
            $errorMessage = 'Google authentication failed: ' . $e->getMessage();

            // Redirect to frontend callback with error
            return redirect(
                $frontendUrl . '/google/callback?' .
                'error=' . urlencode($errorMessage)
            );
        }
    }
}
