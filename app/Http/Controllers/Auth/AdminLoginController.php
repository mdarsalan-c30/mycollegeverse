<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AdminLoginController extends Controller
{
    /**
     * Display the Admin 'Control Tower' Login Terminal.
     */
    public function create()
    {
        return view('auth.admin-login');
    }

    /**
     * Authenticate the Master Authority.
     */
    public function store(LoginRequest $request)
    {
        $request->authenticate();

        // Verify Authority Level 🛡️
        $userRole = strtolower(trim(Auth::user()->role));
        
        if ($userRole !== 'admin') {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            throw ValidationException::withMessages([
                'email' => __('Access Denied. Only Master Authority nodes can access the Admin Terminal.'),
            ]);
        }

        $request->session()->regenerate();

        return redirect()->route('admin.dashboard');
    }

    /**
     * Terminate the Master Authority Session.
     */
    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}
