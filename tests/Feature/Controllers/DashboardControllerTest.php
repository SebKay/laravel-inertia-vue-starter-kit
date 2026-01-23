<?php

use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

use function Pest\Laravel\actingAs;
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
});

describe('Guests', function () {
    test("Can't access the home page", function () {
        get(route('home'))
            ->assertRedirectToRoute('login');
    });
});
