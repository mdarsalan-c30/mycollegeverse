<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class RecruiterRegisterController extends Controller
{
    public function create()
    {
        return view('auth.register-recruiter');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users', function ($attribute, $value, $fail) {
                $blockedDomains = [
                    'gmail.com', 'yahoo.com', 'hotmail.com', 'outlook.com', 
                    'icloud.com', 'rediffmail.com', 'live.com', 'mail.com', 
                    'zoho.com', 'yandex.com', 'protonmail.com'
                ];
                $domain = substr(strrchr($value, "@"), 1);
                if (in_array(strtolower($domain), $blockedDomains)) {
                    $fail('Please use an official company email. Public domains like @'.$domain.' are not allowed for recruiters.');
                }
            }],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'company_name' => ['required', 'string', 'max:255'],
            'company_website' => ['required', 'url', 'max:255'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'recruiter',
            'company_name' => $request->company_name,
            'company_website' => $request->company_website,
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('recruiter.dashboard'));
    }
}
