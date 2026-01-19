<?php

use App\Enums\Permission;
use App\Enums\Role;
use App\Services\RolesAndPermissionsService;
use Spatie\Permission\Models\Permission as SpatiePermission;
use Spatie\Permission\Models\Role as SpatieRole;

dataset('roles', [
    'super admin' => [Role::SUPER_ADMIN],
    'admin' => [Role::ADMIN],
    'user' => [Role::USER],
]);

beforeEach(function () {
    $this->service = resolve(RolesAndPermissionsService::class);
});

describe('sync', function () {
    it('creates all roles from the Role enum', function () {
        $this->service->sync();

        foreach (Role::cases() as $role) {
            expect(SpatieRole::findByName($role->value))->not->toBeNull();
        }

        expect(SpatieRole::count())->toBe(count(Role::cases()));
    });

    it('creates all permissions from the Permission enum', function () {
        $this->service->sync();

        foreach (Permission::cases() as $permission) {
            expect(SpatiePermission::findByName($permission->value))->not->toBeNull();
        }

        expect(SpatiePermission::count())->toBe(count(Permission::cases()));
    });

    it('assigns correct permissions to roles', function (Role $roleEnum) {
        $this->service->sync();

        $role = SpatieRole::findByName($roleEnum->value);
        $expectedPermissions = $roleEnum->permissions();

        expect($role->permissions)->toHaveCount(count($expectedPermissions));

        foreach ($expectedPermissions as $permission) {
            expect($role->hasPermissionTo($permission->value))->toBeTrue();
        }
    })->with('roles');

    it('is idempotent when called multiple times', function () {
        $this->service->sync();
        $this->service->sync();
        $this->service->sync();

        expect(SpatieRole::count())->toBe(count(Role::cases()));
        expect(SpatiePermission::count())->toBe(count(Permission::cases()));
    });

    it('does not duplicate roles or permissions on re-sync', function () {
        $this->service->sync();

        $initialRoleCount = SpatieRole::count();
        $initialPermissionCount = SpatiePermission::count();

        $this->service->sync();

        expect(SpatieRole::count())->toBe($initialRoleCount);
        expect(SpatiePermission::count())->toBe($initialPermissionCount);
    });
});

describe('sync with fresh option', function () {
    it('truncates existing data when fresh is true', function () {
        $this->service->sync();

        expect(SpatieRole::count())->toBeGreaterThan(0);
        expect(SpatiePermission::count())->toBeGreaterThan(0);

        $this->service->sync(fresh: true);

        expect(SpatieRole::count())->toBe(count(Role::cases()));
        expect(SpatiePermission::count())->toBe(count(Permission::cases()));
    });

    it('clears role-permission relationships when fresh is true', function () {
        $this->service->sync();

        $role = SpatieRole::findByName(Role::SUPER_ADMIN->value);
        expect($role->permissions->count())->toBeGreaterThan(0);

        $this->service->sync(fresh: true);

        $role = SpatieRole::findByName(Role::SUPER_ADMIN->value);
        $expectedPermissions = Role::SUPER_ADMIN->permissions();

        expect($role->permissions)->toHaveCount(count($expectedPermissions));
    });
});

describe('getRoles', function () {
    it('returns empty collection when no roles exist', function () {
        $roles = $this->service->getRoles();

        expect($roles)->toBeEmpty();
    });

    it('returns all roles after sync', function () {
        $this->service->sync();

        $roles = $this->service->getRoles();

        expect($roles)->toHaveCount(count(Role::cases()));
    });
});

describe('getPermissions', function () {
    it('returns empty collection when no permissions exist', function () {
        $permissions = $this->service->getPermissions();

        expect($permissions)->toBeEmpty();
    });

    it('returns all permissions after sync', function () {
        $this->service->sync();

        $permissions = $this->service->getPermissions();

        expect($permissions)->toHaveCount(count(Permission::cases()));
    });
});

describe('getRole', function () {
    it('throws exception when role does not exist', function () {
        $this->service->getRole(Role::SUPER_ADMIN);
    })->throws(Spatie\Permission\Exceptions\RoleDoesNotExist::class);

    it('returns the correct role after sync', function () {
        $this->service->sync();

        foreach (Role::cases() as $roleEnum) {
            $role = $this->service->getRole($roleEnum);

            expect($role)->not->toBeNull();
            expect($role->name)->toBe($roleEnum->value);
        }
    });
});

describe('getPermission', function () {
    it('throws exception when permission does not exist', function () {
        $this->service->getPermission(Permission::ACCESS_ADMIN);
    })->throws(Spatie\Permission\Exceptions\PermissionDoesNotExist::class);

    it('returns the correct permission after sync', function () {
        $this->service->sync();

        foreach (Permission::cases() as $permissionEnum) {
            $permission = $this->service->getPermission($permissionEnum);

            expect($permission)->not->toBeNull();
            expect($permission->name)->toBe($permissionEnum->value);
        }
    });
});

describe('permission cache', function () {
    it('clears permission cache after sync', function () {
        $this->service->sync();

        $role = SpatieRole::findByName(Role::SUPER_ADMIN->value);

        expect($role->hasPermissionTo(Permission::ACCESS_ADMIN->value))->toBeTrue();
    });
});
