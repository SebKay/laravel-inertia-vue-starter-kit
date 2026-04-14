<?php

use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;

it("can get it's Filament name", function () {
    $this->seed(RolesAndPermissionsSeeder::class);

    $superUser = User::factory()->super()->create();

    expect($superUser->getFilamentName())->toBe($superUser->name);
});

it('can detect when a user is suspended', function () {
    $user = User::factory()->suspended()->create();

    expect($user->isSuspended())->toBeTrue();
});

it('can suspend a user and rotate their remember token', function () {
    $user = User::factory()->create();
    $originalRememberToken = $user->remember_token;

    $user->suspend();

    expect($user->refresh()->isSuspended())->toBeTrue()
        ->and($user->suspended_at)->not->toBeNull()
        ->and($user->remember_token)->not->toBe($originalRememberToken);
});

it('can reactivate a suspended user', function () {
    $user = User::factory()->suspended()->create();

    $user->reactivate();

    expect($user->refresh()->isSuspended())->toBeFalse()
        ->and($user->suspended_at)->toBeNull();
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
