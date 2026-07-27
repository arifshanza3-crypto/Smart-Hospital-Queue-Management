<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        // Check if user is logged in
        if (!Auth::check()) {
            return redirect('/login')->with('error', 'Please login first.');
        }

        $user = Auth::user();
        $userRole = $user->role;

        // ✅ ADMIN - Can access EVERYTHING (full access)
        // This MUST be the FIRST check - admin bypasses ALL role checks
        if ($userRole === 'admin') {
            return $next($request);
        }

        // If no roles specified, allow access
        if (empty($roles)) {
            return $next($request);
        }

        // Check if user's role is in the allowed roles
        if (in_array($userRole, $roles)) {
            return $next($request);
        }

        // Access denied
        return abort(403, '❌ Access Denied! You do not have permission to access this page.');
    }
}