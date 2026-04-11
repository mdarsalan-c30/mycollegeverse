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

        if ($user->role !== $role) {
            // If a recruiter tries to access student routes
            if ($user->role === 'recruiter') {
                return redirect()->route('recruiter.dashboard');
            }
            
            // If a student tries to access recruiter routes
            if ($role === 'recruiter') {
                return redirect()->route('dashboard');
            }

            abort(403, 'Unauthorized action.');
        }

        return $next($request);
    }
}
