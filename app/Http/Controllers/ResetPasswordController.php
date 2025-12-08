<?php

namespace App\Http\Controllers;

use App\Http\Requests\ResetPassword\ResetPasswordStoreRequest;
use App\Http\Requests\ResetPassword\ResetPasswordUpdateRequest;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class ResetPasswordController extends Controller
{
    public function show(Request $request)
    {
        return inertia('ResetPassword/Show');
    }

    public function store(ResetPasswordStoreRequest $request)
    {
        $status = Password::sendResetLink($request->only('email'));

        throw_if($status !== Password::RESET_LINK_SENT, ValidationException::withMessages([
            'reset_link' => __($status),
        ]));

        session()->regenerate();

        session()->flash('success', __('passwords.sent'));

        return to_route('login');
    }

    public function edit(Request $request, string $token)
    {
        return inertia('ResetPassword/Edit', [
            'token' => $token,
            'email' => $request->string('email'),
        ]);
    }

    public function update(ResetPasswordUpdateRequest $request)
    {
        $status = Password::reset($request->only('token', 'email', 'password', 'password_confirmation'), function (User $user, string $password) {
            $user->forceFill([
                'password' => Hash::make($password),
            ])->setRememberToken(Str::random(60));

            $user->save();

            event(new PasswordReset($user));
        });

        throw_if($status !== Password::PASSWORD_RESET, ValidationException::withMessages([
            'reset' => __($status),
        ]));

        session()->flash('success', __('passwords.reset'));

        return to_route('login');
    }
}
