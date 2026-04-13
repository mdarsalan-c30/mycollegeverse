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
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $role
     * @return mixed
     */
    public function handle(Request $request, Closure $next, string $role)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        $userRole = strtolower(trim($user->role));
        $requiredRole = strtolower(trim($role));

        // Master Authority Bypass: Admins can access any authorized route 👑
        if ($userRole === 'admin') {
            return $next($request);
        }

        if ($userRole !== $requiredRole) {
            // If a recruiter tries to access student routes
            if ($userRole === 'recruiter') {
                return redirect()->route('recruiter.dashboard');
            }
            
            // If a student tries to access recruiter routes
            if ($requiredRole === 'recruiter') {
                return redirect()->route('dashboard');
            }

            abort(403, 'Unauthorized action. Authority level ' . $userRole . ' is insufficient for this node.');
        }

        return $next($request);
    }
}
