<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;

class GoogleController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback()
    {
        try {
            $googleUser = Socialite::driver('google')->stateless()->user();
            
            Log::info('Google authentication attempt', [
                'email' => $googleUser->getEmail(),
                'name' => $googleUser->getName(),
                'google_id' => $googleUser->getId()
            ]);

            // Only find existing users, don't create new ones
            $user = User::where('email', $googleUser->getEmail())->first();

            if (!$user) {
                // User doesn't exist in our system
                Log::warning('Google authentication denied - user not found', [
                    'email' => $googleUser->getEmail()
                ]);
                
                // Log the failed Google sign-in attempt using ActivityLogController
                \App\Http\Controllers\ActivityLogController::log(
                    'google_login_denied',
                    'Google sign-in denied - User not found: ' . $googleUser->getEmail(),
                    null, // No user ID since user doesn't exist
                    [
                        'google_email' => $googleUser->getEmail(),
                        'google_name' => $googleUser->getName(),
                        'google_id' => $googleUser->getId(),
                        'reason' => 'user_not_registered'
                    ]
                );
                
                return redirect()->route('login')->withErrors([
                    'email' => 'Access denied. Your Google account (' . $googleUser->getEmail() . ') is not registered in our system. Please contact your administrator to create an account.'
                ]);
            }

            // Update Google ID if not set
            if (!$user->google_id) {
                $user->google_id = $googleUser->getId();
                $user->save();
                Log::info('Updated Google ID for user', ['user_id' => $user->id]);
            }

            // Login the existing user
            Auth::login($user);
            
            // Log the Google sign-in activity
            $user->logActivity(
                'google_login',
                'User signed in with Google',
                [
                    'google_email' => $googleUser->getEmail(),
                    'google_name' => $googleUser->getName(),
                    'google_id' => $googleUser->getId(),
                    'login_method' => 'google_oauth'
                ]
            );
            
            Log::info('Google authentication successful', [
                'user_id' => $user->id,
                'email' => $user->email
            ]);

            return redirect()->intended('/dashboard');
            
        } catch (\Exception $e) {
            Log::error('Google authentication error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            // Log the Google authentication error
            \App\Http\Controllers\ActivityLogController::log(
                'google_login_error',
                'Google authentication failed: ' . $e->getMessage(),
                null, // No user ID since authentication failed
                [
                    'error_message' => $e->getMessage(),
                    'error_file' => $e->getFile(),
                    'error_line' => $e->getLine()
                ]
            );
            
            return redirect()->route('login')->withErrors([
                'email' => 'Google authentication failed. Please try again or use your email and password.'
            ]);
        }
    }
}
