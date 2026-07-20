<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!Auth::check()) {
            return redirect('/login');
        }

        $user = Auth::user();

        if (in_array($user->role, $roles)) {
            return $next($request);
        }

        // If user is admin, allow all admin routes
        if ($user->role === 'admin' && in_array('admin', $roles)) {
            return $next($request);
        }

        // If user is staff, allow staff routes
        if ($user->role === 'staff' && in_array('staff', $roles)) {
            return $next($request);
        }

        return abort(403, 'Unauthorized access.');
    }
}