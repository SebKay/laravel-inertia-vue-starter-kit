<?php

use Inertia\Testing\AssertableInertia as Assert;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

describe('Users', function () {
    test('Super admins are redirected to confirm their password before reaching the password test page', function () {
        actingAs(superUser())
            ->get(route('password-test'))
            ->assertRedirectToRoute('password.confirm');
    });

    test('Super admins can access the password test page after confirming their password', function () {
        actingAs(superUser());

        session()->put('auth.password_confirmed_at', time());

        get(route('password-test'))
            ->assertOk()
            ->assertInertia(
                fn (Assert $page) => $page
                    ->component('Password')
            );
    });

    test('Admins cannot access the password test page', function () {
        actingAs(adminUser())
            ->get(route('password-test'))
            ->assertForbidden();
    });
});

describe('Guests', function () {
    test('Guests are redirected to login', function () {
        get(route('password-test'))
            ->assertRedirectToRoute('login');
    });
});
