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

        // ✅ ADMIN - Can access everything
        if ($user->role === 'admin') {
            return $next($request);
        }

        // ✅ STAFF - Can access staff and website only
        if ($user->role === 'staff') {
            // Check if trying to access admin routes
            if ($request->route()->getPrefix() === 'admin') {
                return abort(403, '❌ Access Denied! Staff cannot access Admin Panel.');
            }
            // Check if role allows staff
            if (in_array('staff', $roles) || in_array('user', $roles)) {
                return $next($request);
            }
            return abort(403, '❌ Access Denied!');
        }

        // ✅ USER - Can access website only
        if ($user->role === 'user' || $user->role === 'patient') {
            // Check if trying to access admin or staff routes
            if ($request->route()->getPrefix() === 'admin') {
                return abort(403, '❌ Access Denied! Users cannot access Admin Panel.');
            }
            if ($request->route()->getPrefix() === 'staff') {
                return abort(403, '❌ Access Denied! Users cannot access Staff Panel.');
            }
            // Only allow user routes
            if (in_array('user', $roles) || in_array('patient', $roles)) {
                return $next($request);
            }
            return abort(403, '❌ Access Denied!');
        }

        return abort(403, '❌ Unauthorized access.');
    }
}