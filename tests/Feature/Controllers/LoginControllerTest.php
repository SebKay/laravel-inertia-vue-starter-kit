<?php

use App\Enums\Environment;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Inertia\Testing\AssertableInertia as Assert;

use function Pest\Faker\fake;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertAuthenticated;
use function Pest\Laravel\assertGuest;
use function Pest\Laravel\from;
use function Pest\Laravel\get;
use function Pest\Laravel\post;

mutates(App\Http\Controllers\LoginController::class);

describe('Users', function () {
    test("Can't access the login page", function () {
        actingAs(User::factory()->create())
            ->get(route('login'))
            ->assertRedirectToRoute('home');
    });
});

describe('Guests', function () {
    test('Can access the login page', function () {
        get(route('login'))
            ->assertOk()
            ->assertInertia(
                fn (Assert $page) => $page
                    ->component('Login/Show')
                    ->where('email', config('seed.users.super.email'))
                    ->where('password', config('seed.users.super.password'))
                    ->where('remember', true)
                    ->where('redirect', '')
            );
    });

    test('Props are not passed to the show page in production', function () {
        app()->instance('env', Environment::PRODUCTION->value);

        get(route('login'))
            ->assertOk()
            ->assertInertia(
                fn (Assert $page) => $page
                    ->component('Login/Show')
                    ->missing('email')
                    ->missing('password')
                    ->missing('remember')
                    ->missing('redirect')
            );
    });

    test('Can login', function () {
        $password = fake()->password();
        $user = User::factory()->create([
            'password' => Hash::make($password),
        ]);

        assertGuest();

        post(route('login'), [
            'email' => $user->email,
            'password' => $password,
        ])
            ->assertSessionDoesntHaveErrors()
            ->assertRedirectToRoute('home');

        assertAuthenticated();
    });

    test('Can be redirected after login', function () {
        $password = fake()->password();
        $user = User::factory()->create([
            'password' => Hash::make($password),
        ]);

        $redirectUrl = 'https://www.google.com/';

        assertGuest();

        from(route('login'))
            ->post(route('login'), [
                'email' => $user->email,
                'password' => $password,
                'redirect' => $redirectUrl,
            ])
            ->assertSessionDoesntHaveErrors()
            ->assertRedirect($redirectUrl);

        assertAuthenticated();
    });

    test("Can't login with invalid credentials", function () {
        $user = User::factory()->create([
            'password' => Hash::make(fake()->password()),
        ]);

        assertGuest();

        from(route('login'))
            ->post(route('login'), [
                'email' => $user->email,
                'password' => 'test',
            ])
            ->assertSessionHasErrors([
                'email' => __('auth.failed'),
            ])
            ->assertRedirectToRoute('login');

        assertGuest();
    });
});
