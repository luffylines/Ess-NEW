<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureProfileIsComplete
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
        $user = Auth::user();
        if ($user) {
            // Check if profile is incomplete (customize fields as needed)
            if (empty($user->phone) || empty($user->gender) || empty($user->address)) {
                // Prevent redirect loop
                if (!$request->routeIs('employees.complete') && !$request->routeIs('employees.complete.store')) {
                    return redirect()->route('employees.complete', ['token' => $user->remember_token ?? '']);
                }
            }
        }
        return $next($request);
    }
}
