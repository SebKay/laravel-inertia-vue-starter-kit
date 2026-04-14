<?php

namespace App\Http\Controllers;

use App\Enums\Environment;
use App\Enums\Role;
use App\Http\Requests\Register\RegisterStoreRequest;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Inertia\Response;

class RegisterController extends Controller
{
    public function show(): Response|array
    {
        return inertia('Register/Show', app()->environment([Environment::LOCAL->value, Environment::TESTING->value]) ? [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'password' => '123456',
        ] : []);
    }

    public function store(RegisterStoreRequest $request): RedirectResponse
    {
        $user = new User($request->safe()->only('name', 'email'));

        $user->password = $request->validated('password');
        $user->save();

        $user->assignRole(Role::USER->value);

        auth()->guard()->loginUsingId($user->id);

        event(new Registered($user));

        return to_route('home');
    }
}
