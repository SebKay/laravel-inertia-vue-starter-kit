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
                    ->where('auth.user.data.attributes.emailVerified', true)
                    ->missing('dashboard.stats')
                    ->missing('dashboard.superAdmin')
                    ->loadDeferredProps('dashboard-stats', fn (Assert $reload) => $reload
                        ->has('dashboard.stats')
                        ->where('dashboard.stats.totalUsers', User::count())
                    )
            );
    });

    test('Super admins can load the dashboard overview section on demand', function () {
        $superAdmin = superAdminUser();
        adminUser();
        User::factory()->create();

        $response = actingAs($superAdmin)->get(route('home'));

        $response->assertOk()
            ->assertInertia(
                fn (Assert $page) => $page
                    ->component('Dashboard/Index')
                    ->missing('dashboard.superAdmin')
                    ->reloadOnly('dashboard.superAdmin', fn (Assert $reload) => $reload
                        ->has('dashboard.superAdmin')
                        ->where('dashboard.superAdmin.privilegedUsers', 2)
                        ->has('dashboard.superAdmin.latestUsers')
                    )
            );
    });
});

describe('Guests', function () {
    test("Can't access the home page", function () {
        get(route('home'))
            ->assertRedirectToRoute('login');
    });
});
