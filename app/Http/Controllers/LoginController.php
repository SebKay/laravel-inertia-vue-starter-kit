<?php

namespace App\Http\Controllers;

use App\Enums\Environment;
use App\Http\Requests\Login\LoginStoreRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function show(Request $request)
    {
        return inertia('Login/Show', app()->environment([Environment::LOCAL->value, Environment::TESTING->value]) ? [
            'email' => config('seed.users.super.email'),
            'password' => config('seed.users.super.password'),
            'remember' => true,
            'redirect' => $request->query('redirect', ''),
        ] : []);
    }

    public function store(LoginStoreRequest $request)
    {
        throw_if(
            ! auth()->guard()->attempt($request->only('email', 'password'), $request->only('remember')),
            ValidationException::withMessages([
                'email' => __('auth.failed'),
            ])
        );

        session()->regenerate();

        if ($request->validated('redirect')) {
            return redirect()->to($request->validated('redirect'));
        }

        return redirect()->intended(route('home'));
    }
}
