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
            $profileIncomplete = empty($user->phone) || empty($user->gender) || empty($user->address);
            $emailNotVerified = !$user->hasVerifiedEmail();

            // Only force for users who are not verified or have incomplete profile
            if (($profileIncomplete || $emailNotVerified)
                && !$request->routeIs('employees.complete')
                && !$request->routeIs('employees.complete.store')
                && !$request->routeIs('verification.notice')
                && !$request->routeIs('verification.send')
                && !$request->routeIs('verification.verify')) {
                // If email not verified, redirect to verification notice
                if ($emailNotVerified) {
                    return redirect()->route('verification.notice');
                }
                // If profile incomplete, redirect to complete profile
                if ($profileIncomplete) {
                    return redirect()->route('employees.complete', ['token' => $user->remember_token ?? '']);
                }
            }
        }
        return $next($request);
    }
}
