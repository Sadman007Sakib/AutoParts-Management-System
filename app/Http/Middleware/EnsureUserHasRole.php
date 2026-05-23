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
        return redirect()->route('login');
    }

    if (! empty($roles) && ! in_array($user->role, $roles)) {
        abort(403, 'Unauthorized access');
    }

    return $next($request);
}
}
