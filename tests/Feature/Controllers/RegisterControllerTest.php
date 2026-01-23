<?php

use App\Enums\Environment;
use App\Enums\Role;
use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertAuthenticated;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertGuest;
use function Pest\Laravel\from;
use function Pest\Laravel\get;
use function Pest\Laravel\post;

describe('Users', function () {
    test("Can't access the register page", function () {
        actingAs(User::factory()->create())
            ->get(route('register'))
            ->assertRedirectToRoute('home');
    });
});

describe('Guests', function () {
    test('Can access the register page', function () {
        get(route('register'))
            ->assertOk()
            ->assertInertia(
                fn (Assert $page) => $page
                    ->component('Register/Show')
                    ->has('name')
                    ->has('email')
                    ->has('password')
            );
    });

    test('Props are not passed to the show page in production', function () {
        app()->instance('env', Environment::PRODUCTION->value);

        get(route('register'))
            ->assertOk()
            ->assertInertia(
                fn (Assert $page) => $page
                    ->component('Register/Show')
                    ->missing('name')
                    ->missing('email')
                    ->missing('password')
            );
    });

    test('Can register', function () {
        $email = fake()->email();

        assertGuest();

        post(route('register.store'), [
            'name' => fake()->name(),
            'email' => $email,
            'password' => 'Pa$$word12345#',
        ])
            ->assertSessionDoesntHaveErrors()
            ->assertRedirectToRoute('home');

        assertDatabaseHas('users', [
            'email' => $email,
        ]);

        expect(User::where('email', $email)->firstOrFail()->roles->first()->name)->toBe(Role::USER->value);

        assertAuthenticated();
    });

    test("Can't register with an email that already exists", function () {
        $email = 'jim@test.com';

        User::factory()->create([
            'email' => $email,
        ]);

        from(route('register'))
            ->post(route('register.store'), [
                'name' => fake()->name(),
                'email' => $email,
                'password' => 'P$ssword12345#',
            ])
            ->assertSessionHasErrors([
                'email' => __('validation.unique', ['attribute' => 'email']),
            ])
            ->assertRedirectToRoute('register');
    });
});
