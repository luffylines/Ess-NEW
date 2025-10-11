<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HrMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Check if user is authenticated and has HR role
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // Check if user has HR or Manager role (both can access HR functionalities)
        $user = Auth::user();
        $allowedRoles = ['hr', 'HR', 'manager', 'Manager'];
        
        if (!in_array($user->role, $allowedRoles)) {
            abort(403, 'Unauthorized access. HR or Manager role required.');
        }

        return $next($request);
    }
}
