<?php

use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertGuest;
use function Pest\Laravel\get;

describe('Users', function () {
    test('Can access the home page', function () {
        actingAs(User::factory()->create())
            ->get(route('home'))
            ->assertOk()
            ->assertInertia(
                fn (Assert $page) => $page
                    ->component('Dashboard/Index')
            );
    });

    test('Suspended users are logged out on their next authenticated request', function () {
        $user = User::factory()->suspended()->create();

        actingAs($user)
            ->get(route('home'))
            ->assertRedirectToRoute('login')
            ->assertInertiaFlash('toast.type', 'warning')
            ->assertInertiaFlash('toast.message', __('auth.suspended'));

        assertGuest();
    });
});

describe('Guests', function () {
    test("Can't access the home page", function () {
        get(route('home'))
            ->assertRedirectToRoute('login');
    });
});
