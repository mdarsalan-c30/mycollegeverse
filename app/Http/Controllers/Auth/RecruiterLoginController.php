<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class RecruiterLoginController extends Controller
{
    public function create()
    {
        return view('auth.login-recruiter');
    }

    public function store(LoginRequest $request)
    {
        $request->authenticate();

        if (Auth::user()->role !== 'recruiter') {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            throw ValidationException::withMessages([
                'email' => __('This portal is for Recruiters only. Students should use the main login.'),
            ]);
        }

        $request->session()->regenerate();

        return redirect()->route('recruiter.dashboard');
    }

    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/recruiter/login');
    }
}
