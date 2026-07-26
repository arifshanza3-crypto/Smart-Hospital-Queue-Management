<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  mixed  ...$roles
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        // Check if user is logged in
        if (!Auth::check()) {
            return redirect('/login')->with('error', 'Please login first.');
        }

        $user = Auth::user();
        $userRole = $user->role;
        $routePrefix = $request->route()->getPrefix();
        $routeName = $request->route()->getName();

        // ✅ ADMIN - Can access everything (full access)
        if ($userRole === 'admin') {
            return $next($request);
        }

        // ✅ STAFF - Can access staff routes and website, but NOT admin routes
        if ($userRole === 'staff') {
            // Block access to admin routes
            if ($routePrefix === 'admin' || str_starts_with($routeName, 'admin.')) {
                return abort(403, '❌ Access Denied! Staff cannot access Admin Panel.');
            }
            
            // Allow access to staff routes and website
            if (in_array('staff', $roles) || in_array('user', $roles) || $routePrefix === null) {
                return $next($request);
            }
            
            return abort(403, '❌ Access Denied! You do not have permission to access this page.');
        }

        // ✅ USER / PATIENT - Can access website only
        if ($userRole === 'user' || $userRole === 'patient') {
            // Block access to admin routes
            if ($routePrefix === 'admin' || str_starts_with($routeName, 'admin.')) {
                return abort(403, '❌ Access Denied! Users cannot access Admin Panel.');
            }
            
            // Block access to staff routes
            if ($routePrefix === 'staff' || str_starts_with($routeName, 'staff.')) {
                return abort(403, '❌ Access Denied! Users cannot access Staff Panel.');
            }
            
            // Allow access to public routes (website)
            if (in_array('user', $roles) || in_array('patient', $roles) || $routePrefix === null) {
                return $next($request);
            }
            
            return abort(403, '❌ Access Denied! You do not have permission to access this page.');
        }

        // ✅ If no role matches, deny access
        return abort(403, '❌ Unauthorized access. Please contact administrator.');
    }
}