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

        // Assuming you have a role field or relationship
        // Adjust this logic based on your user role implementation
        $user = Auth::user();
        if ($user->role !== 'hr' && $user->role !== 'HR') {
            abort(403, 'Unauthorized access.');
        }

        return $next($request);
    }
}
