<?php

namespace App\Http\Controllers;

use App\Enums\Environment;
use App\Http\Requests\Login\LoginStoreRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

class LoginController extends Controller
{
    public function show(Request $request): Response|array
    {
        return inertia('Login/Show', app()->environment([Environment::LOCAL->value, Environment::TESTING->value]) ? [
            'email' => config('seed.users.super.email'),
            'password' => config('seed.users.super.password'),
            'remember' => true,
            'redirect' => $request->query('redirect', ''),
        ] : []);
    }

    public function store(LoginStoreRequest $request): RedirectResponse
    {
        $candidateUser = User::query()
            ->where('email', $request->validated('email'))
            ->first();

        if (
            $candidateUser !== null &&
            Hash::check($request->validated('password'), $candidateUser->password) &&
            $candidateUser->isSuspended()
        ) {
            Inertia::flash('toast', [
                'type' => 'warning',
                'message' => __('auth.suspended'),
            ]);

            return to_route('login');
        }

        throw_if(
            ! auth()->guard()->attempt($request->safe()->only('email', 'password'), $request->boolean('remember')),
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
