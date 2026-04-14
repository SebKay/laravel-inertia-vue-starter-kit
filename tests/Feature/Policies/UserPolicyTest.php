<?php

use App\Enums\Role;
use App\Models\User;

describe('Super Admin', function () {
    test('can view any users', function () {
        $super = User::factory()->create();
        $super->assignRole(Role::SUPER);

        expect($super->can('viewAny', User::class))->toBeTrue();
    });

    test('can view any user', function () {
        $super = User::factory()->create();
        $super->assignRole(Role::SUPER);
        $other = User::factory()->create();
        $other->assignRole(Role::ADMIN);

        expect($super->can('view', $other))->toBeTrue();
    });

    test('can create users', function () {
        $super = User::factory()->create();
        $super->assignRole(Role::SUPER);

        expect($super->can('create', User::class))->toBeTrue();
    });

    test('can update any user including other supers', function () {
        $super = User::factory()->create();
        $super->assignRole(Role::SUPER);
        $otherSuper = User::factory()->create();
        $otherSuper->assignRole(Role::SUPER);

        expect($super->can('update', $otherSuper))->toBeTrue();
    });

    test('can delete other users', function () {
        $super = User::factory()->create();
        $super->assignRole(Role::SUPER);
        $admin = User::factory()->create();
        $admin->assignRole(Role::ADMIN);

        expect($super->can('delete', $admin))->toBeTrue();
    });

    test('cannot delete themselves', function () {
        $super = User::factory()->create();
        $super->assignRole(Role::SUPER);

        expect($super->can('delete', $super))->toBeFalse();
    });

    test('can suspend and reactivate regular users', function () {
        $super = User::factory()->create();
        $super->assignRole(Role::SUPER);
        $user = User::factory()->create();
        $user->assignRole(Role::USER);

        expect($super->can('suspend', $user))->toBeTrue()
            ->and($super->can('reactivate', $user))->toBeTrue();
    });

    test('cannot suspend or reactivate admin users', function () {
        $super = User::factory()->create();
        $super->assignRole(Role::SUPER);
        $admin = User::factory()->create();
        $admin->assignRole(Role::ADMIN);

        expect($super->can('suspend', $admin))->toBeFalse()
            ->and($super->can('reactivate', $admin))->toBeFalse();
    });

    test('cannot suspend or reactivate super users', function () {
        $super = User::factory()->create();
        $super->assignRole(Role::SUPER);
        $otherSuper = User::factory()->create();
        $otherSuper->assignRole(Role::SUPER);

        expect($super->can('suspend', $otherSuper))->toBeFalse()
            ->and($super->can('reactivate', $otherSuper))->toBeFalse();
    });
});

describe('Admin', function () {
    test('can view any users', function () {
        $admin = User::factory()->create();
        $admin->assignRole(Role::ADMIN);

        expect($admin->can('viewAny', User::class))->toBeTrue();
    });

    test('can create users', function () {
        $admin = User::factory()->create();
        $admin->assignRole(Role::ADMIN);

        expect($admin->can('create', User::class))->toBeTrue();
    });

    test('can update non-super users', function () {
        $admin = User::factory()->create();
        $admin->assignRole(Role::ADMIN);
        $user = User::factory()->create();
        $user->assignRole(Role::USER);

        expect($admin->can('update', $user))->toBeTrue();
    });

    test('cannot update super admins', function () {
        $admin = User::factory()->create();
        $admin->assignRole(Role::ADMIN);
        $super = User::factory()->create();
        $super->assignRole(Role::SUPER);

        expect($admin->can('update', $super))->toBeFalse();
    });

    test('can delete non-super users', function () {
        $admin = User::factory()->create();
        $admin->assignRole(Role::ADMIN);
        $user = User::factory()->create();
        $user->assignRole(Role::USER);

        expect($admin->can('delete', $user))->toBeTrue();
    });

    test('cannot delete super admins', function () {
        $admin = User::factory()->create();
        $admin->assignRole(Role::ADMIN);
        $super = User::factory()->create();
        $super->assignRole(Role::SUPER);

        expect($admin->can('delete', $super))->toBeFalse();
    });

    test('cannot delete themselves', function () {
        $admin = User::factory()->create();
        $admin->assignRole(Role::ADMIN);

        expect($admin->can('delete', $admin))->toBeFalse();
    });

    test('can suspend and reactivate regular users', function () {
        $admin = User::factory()->create();
        $admin->assignRole(Role::ADMIN);
        $user = User::factory()->create();
        $user->assignRole(Role::USER);

        expect($admin->can('suspend', $user))->toBeTrue()
            ->and($admin->can('reactivate', $user))->toBeTrue();
    });

    test('cannot suspend or reactivate admin users', function () {
        $admin = User::factory()->create();
        $admin->assignRole(Role::ADMIN);
        $otherAdmin = User::factory()->create();
        $otherAdmin->assignRole(Role::ADMIN);

        expect($admin->can('suspend', $otherAdmin))->toBeFalse()
            ->and($admin->can('reactivate', $otherAdmin))->toBeFalse();
    });

    test('cannot suspend or reactivate super users', function () {
        $admin = User::factory()->create();
        $admin->assignRole(Role::ADMIN);
        $super = User::factory()->create();
        $super->assignRole(Role::SUPER);

        expect($admin->can('suspend', $super))->toBeFalse()
            ->and($admin->can('reactivate', $super))->toBeFalse();
    });
});

describe('Regular User', function () {
    test('cannot view any users', function () {
        $user = User::factory()->create();
        $user->assignRole(Role::USER);

        expect($user->can('viewAny', User::class))->toBeFalse();
    });

    test('cannot create users', function () {
        $user = User::factory()->create();
        $user->assignRole(Role::USER);

        expect($user->can('create', User::class))->toBeFalse();
    });

    test('cannot update users', function () {
        $user = User::factory()->create();
        $user->assignRole(Role::USER);
        $other = User::factory()->create();
        $other->assignRole(Role::USER);

        expect($user->can('update', $other))->toBeFalse();
    });

    test('cannot delete users', function () {
        $user = User::factory()->create();
        $user->assignRole(Role::USER);
        $other = User::factory()->create();
        $other->assignRole(Role::USER);

        expect($user->can('delete', $other))->toBeFalse();
    });

    test('cannot suspend or reactivate users', function () {
        $user = User::factory()->create();
        $user->assignRole(Role::USER);
        $other = User::factory()->create();
        $other->assignRole(Role::USER);

        expect($user->can('suspend', $other))->toBeFalse()
            ->and($user->can('reactivate', $other))->toBeFalse();
    });
});
