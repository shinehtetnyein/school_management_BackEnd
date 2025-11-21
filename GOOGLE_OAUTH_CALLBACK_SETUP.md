# Backend Google Callback Modification Guide

## Current Implementation Status

The backend `GoogleAuthController` currently returns JSON with user and token data. However, for proper frontend integration, the callback endpoint needs to redirect the frontend with the token and user data in URL parameters.

## Recommended Backend Modification

Update `Modules/Authentication/app/Http/Controllers/GoogleAuthController.php` callback method to redirect to frontend instead of returning JSON:

```php
public function callback()
{
    try {
        $googleUser = Socialite::driver('google')->user();

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

        // Prepare user data
        $userData = json_encode([
            'id' => $user->id,
            'uuid' => $user->uuid,
            'name' => $user->name,
            'email' => $user->email,
            'profile_photo' => $user->profile_photo,
        ]);

        // Get frontend URL from environment or config
        $frontendUrl = env('FRONTEND_URL', 'http://localhost:5173');

        // Redirect to frontend callback handler with token and user data
        return redirect(
            $frontendUrl . '/google/callback?' .
            'token=' . urlencode($token) .
            '&user=' . urlencode($userData)
        );

    } catch (\Exception $e) {
        // Redirect with error
        $frontendUrl = env('FRONTEND_URL', 'http://localhost:5173');
        $errorMessage = 'Google authentication failed: ' . $e->getMessage();

        return redirect(
            $frontendUrl . '/google/callback?' .
            'error=' . urlencode($errorMessage)
        );
    }
}
```

## Environment Configuration

Add to your `.env` file:

```
FRONTEND_URL=http://localhost:5173
```

Change the port (5173 or your actual frontend port) as needed for your development/production environment.

## Alternative Approach (Token-Based)

If you prefer using a separate API endpoint for token exchange instead of URL parameters, the frontend `GoogleAuthService` has a `handleGoogleCallback()` method that can be used:

**Backend would POST to:**

```php
Route::post('/api/v1/google/callback', [GoogleAuthController::class, 'callbackApi']);
```

**Frontend would call after getting Google credential:**

```javascript
const result = await googleAuthService.handleGoogleCallback(credentialResponse);
```

However, the redirect-based approach (first option) is simpler for OAuth flow and doesn't require JWT on frontend.

## Testing the Endpoint

1. Start both backend and frontend servers
2. Click "Continue with Google" on login page
3. Complete Google OAuth flow
4. Verify you're redirected to `http://localhost:5173/google/callback?token=...&user=...`
5. Verify authentication completes and you're on dashboard

## Troubleshooting

- **Blank page after Google auth**: Check browser console for errors
- **Redirect loop**: Verify FRONTEND_URL matches your actual frontend URL
- **Missing token/user data**: Check that backend is properly generating and passing them
- **CORS issues**: Backend should not have CORS restrictions for callback
