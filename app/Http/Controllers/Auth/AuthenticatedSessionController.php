<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

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
         $request->validate([
        'email' => 'required|email',
        'password' => 'required',
        'role' => 'required|in:employee,admin,hr',
    ]);

    // Retrieve user by email
    $user = User::where('email', $request->email)->first();

    // Check if user exists and password matches
    if (!$user || !\Hash::check($request->password, $user->password)) {
        return back()->withErrors([
            'email' => 'The provided credentials are incorrect.',
        ]);
    }

    // Check if the selected role matches the user's actual role
    if ($user->role !== $request->role) {
        return back()->withErrors([
            'role' => 'You are not allowed to log in as ' . ucfirst($request->role),
        ]);
    }

        // Login the user
        Auth::login($user, $request->boolean('remember'));

        $request->authenticate();

        $request->session()->regenerate();

        return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
