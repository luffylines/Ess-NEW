<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Http\Controllers\ActivityLogController;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        // Get remembered login if exists
        $rememberedLogin = request()->cookie('remembered_login');
        
        return view('auth.login', compact('rememberedLogin'));
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        // Authenticate the user
        $request->authenticate();

        // Regenerate session to prevent fixation
        $request->session()->regenerate();

        // Log the login activity with correct user ID and IP
        ActivityLogController::log(
            'login', 
            'User logged in', 
            Auth::id()
        );

        // Handle remember me functionality
        $response = redirect()->intended(route('dashboard', absolute: false));
        
        if ($request->has('remember') && !$request->has('clear_remembered')) {
            // Store login for next time (expires in 30 days)
            $response->withCookie(cookie('remembered_login', $request->login, 30 * 24 * 60));
        } elseif (!$request->has('remember') || $request->has('clear_remembered')) {
            // Clear any existing remembered login
            $response->withCookie(cookie()->forget('remembered_login'));
        }

        return $response;
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $user = Auth::user();

        if ($user) {
            // Log logout activity with correct user ID and IP
            ActivityLogController::log(
                'logout', 
                'User logged out', 
                $user->id
            );
        }

        // Logout user
        Auth::guard('web')->logout();

        // Invalidate and regenerate session token
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // The remembered login cookie is preserved - it will only be cleared 
        // when user unchecks "Remember Me" on next login
        return redirect('/login');
    }
}
