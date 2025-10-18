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
        return view('auth.login');
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

        // Redirect to intended page
        return redirect()->intended(route('dashboard', absolute: false));
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

        return redirect('/');
    }
}
