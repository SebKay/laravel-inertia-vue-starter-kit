<?php

use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;

describe('With Roles and Permissions', function () {
    beforeEach(function () {
        $this->seed(RolesAndPermissionsSeeder::class);
    });

    it("can only access Filament if it's a \"super-admin\" or \"admin\"", function () {
        $superAdminUser = User::factory()->superAdmin()->create();
        $adminUser = User::factory()->admin()->create();
        $user = User::factory()->user()->create();

        expect($superAdminUser->canAccessPanel())->toBeTrue();
        expect($adminUser->canAccessPanel())->toBeTrue();
        expect($user->canAccessPanel())->toBeFalse();
    });
});
