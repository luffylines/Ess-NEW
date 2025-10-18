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
            
            return redirect()->route('login')->withErrors([
                'email' => 'Google authentication failed. Please try again or use your email and password.'
            ]);
        }
    }
}
