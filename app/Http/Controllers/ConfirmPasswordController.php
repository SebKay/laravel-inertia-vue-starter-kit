<?php

namespace App\Http\Controllers;

use App\Http\Requests\ConfirmPassword\ConfirmPasswordStoreRequest;
use Illuminate\Http\RedirectResponse;
use Inertia\Response;

class ConfirmPasswordController extends Controller
{
    public function show(): Response
    {
        return inertia('Password/Show');
    }

    public function store(ConfirmPasswordStoreRequest $request): RedirectResponse
    {
        session()->put('auth.password_confirmed_at', time());

        return redirect()->intended(route('home'));
    }
}
