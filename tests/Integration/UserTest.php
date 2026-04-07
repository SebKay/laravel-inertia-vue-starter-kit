<?php

use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;

it("can get it's Filament name", function () {
    $this->seed(RolesAndPermissionsSeeder::class);

    $superUser = User::factory()->super()->create();

    expect($superUser->getFilamentName())->toBe($superUser->name);
});

describe('With Roles and Permissions', function () {
    beforeEach(function () {
        $this->seed(RolesAndPermissionsSeeder::class);
    });

    it("can only access Filament if it's a \"super\" or \"admin\"", function () {
        $superUser = User::factory()->super()->create();
        $adminUser = User::factory()->admin()->create();
        $user = User::factory()->user()->create();

        expect($superUser->canAccessPanel())->toBeTrue();
        expect($adminUser->canAccessPanel())->toBeTrue();
        expect($user->canAccessPanel())->toBeFalse();
    });
});
