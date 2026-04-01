<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Inertia\Testing\AssertableInertia as Assert;

use function Pest\Faker\fake;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;
use function Pest\Laravel\post;

describe('Users', function () {
    test('Can access the confirm password page', function () {
        $user = User::factory()->create();

        actingAs($user)
            ->get(route('password.confirm'))
            ->assertOk()
            ->assertSee('<title>'.config('app.name').'</title>', escape: false)
            ->assertSee('id="app"', escape: false)
            ->assertInertia(
                fn (Assert $page) => $page
                    ->component('Password/Show')
            );
    });

    test('Can confirm password and be redirected home', function () {
        $password = fake()->password();
        $user = User::factory()->create([
            'password' => Hash::make($password),
        ]);

        actingAs($user)
            ->fromRoute('password.confirm')
            ->post(route('password.confirm.store'), [
                'password' => $password,
            ])
            ->assertSessionDoesntHaveErrors()
            ->assertRedirectToRoute('home');

        expect(session('auth.password_confirmed_at'))->toBeInt();
    });

    test('Can confirm password and be redirected to the intended URL', function () {
        $user = superAdminUser();
        $password = config('seed.users.super.password');

        actingAs($user)
            ->get(route('elements'))
            ->assertRedirect(route('password.confirm'));

        actingAs($user)
            ->post(route('password.confirm.store'), [
                'password' => $password,
            ])
            ->assertSessionDoesntHaveErrors()
            ->assertRedirect(route('elements'));
    });

    test("Can't confirm with an incorrect password", function () {
        $user = User::factory()->create([
            'password' => Hash::make(fake()->password()),
        ]);

        actingAs($user)
            ->fromRoute('password.confirm')
            ->post(route('password.confirm.store'), [
                'password' => 'wrong-password',
            ])
            ->assertSessionHasErrors([
                'password' => __('validation.current_password'),
            ])
            ->assertRedirectToRoute('password.confirm');

        expect(session('auth.password_confirmed_at'))->toBeNull();
    });

    test("Can't confirm without a password", function () {
        $user = User::factory()->create();

        actingAs($user)
            ->fromRoute('password.confirm')
            ->post(route('password.confirm.store'), [])
            ->assertSessionHasErrors([
                'password' => __('validation.required', ['attribute' => 'password']),
            ])
            ->assertRedirectToRoute('password.confirm');
    });
});

describe('Guests', function () {
    test("Can't access the confirm password page", function () {
        get(route('password.confirm'))
            ->assertRedirectToRoute('login');
    });

    test("Can't submit password confirmation", function () {
        post(route('password.confirm.store'))
            ->assertSessionDoesntHaveErrors()
            ->assertRedirectToRoute('login');
    });
});
