<?php

use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

describe('Users', function () {
    test('Authenticated users are redirected to confirm their password before reaching the password test page', function () {
        actingAs(adminUser())
            ->get(route('password-test'))
            ->assertRedirectToRoute('password.confirm');
    });

    test('Authenticated users can access the password test page after confirming their password', function () {
        actingAs(adminUser());

        session()->put('auth.password_confirmed_at', time());

        get(route('password-test'))
            ->assertOk()
            ->assertInertia(
                fn (Assert $page) => $page
                    ->component('Password')
            );
    });

    test('Regular users can access the password test page after confirming their password', function () {
        $user = User::whereEmail(config('seed.users.user.email'))->firstOrFail();

        actingAs($user);

        session()->put('auth.password_confirmed_at', time());

        get(route('password-test'))
            ->assertOk()
            ->assertInertia(
                fn (Assert $page) => $page
                    ->component('Password')
            );
    });
});

describe('Guests', function () {
    test('Guests are redirected to login', function () {
        get(route('password-test'))
            ->assertRedirectToRoute('login');
    });
});
