<?php

namespace App\Http\Controllers;

use App\Http\Requests\ConfirmPassword\ConfirmPasswordStoreRequest;

class ConfirmPasswordController extends Controller
{
    public function show()
    {
        return inertia('Password/Show');
    }

    public function store(ConfirmPasswordStoreRequest $request)
    {
        session()->put('auth.password_confirmed_at', time());

        return redirect()->intended(route('home'));
    }
}
