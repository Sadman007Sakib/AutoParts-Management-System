<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureUserHasRole
{
    /**
     * Handle an incoming request.
     *
     * Usage in routes: 'role:admin' or 'role:admin,coordinator'
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $user = Auth::user();

        if (! $user) {
            return redirect()->route('login')->with('error', 'You must be logged in to access this page.');
        }

        // if route didn't require any specific role, allow access
        if (empty($roles)) {
            return $next($request);
        }

        if (! in_array($user->role, $roles)) {
            return redirect()->route('home')->with('error', 'You are not authorized to access this page.');
        }

        return $next($request);
    }
}
