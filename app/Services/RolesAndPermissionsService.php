<?php

namespace App\Services;

use App\Enums\Permission;
use App\Enums\Role;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission as SpatiePermission;
use Spatie\Permission\Models\Role as SpatieRole;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsService
{
    protected Collection $roles;

    protected Collection $permissions;

    public function __construct(
        protected PermissionRegistrar $permissionRegistrar,
    ) {}

    /**
     * Sync all roles and permissions from enums to the database.
     */
    public function sync(bool $fresh = false): void
    {
        $this->permissionRegistrar->forgetCachedPermissions();

        if ($fresh) {
            $this->truncate();
        }

        $this->syncRoles();
        $this->syncPermissions();
        $this->assignPermissionsToRoles();

        $this->permissionRegistrar->forgetCachedPermissions();
    }

    /**
     * Truncate all roles and permissions tables for a fresh start.
     */
    protected function truncate(): void
    {
        $tableNames = config('permission.table_names');

        // Clear pivot tables first to avoid foreign key constraint issues
        DB::table($tableNames['role_has_permissions'])->truncate();
        DB::table($tableNames['model_has_roles'])->truncate();
        DB::table($tableNames['model_has_permissions'])->truncate();

        // Then truncate roles and permissions tables for a complete fresh start
        DB::table($tableNames['roles'])->truncate();
        DB::table($tableNames['permissions'])->truncate();
    }

    /**
     * Sync roles from the Role enum to the database.
     */
    protected function syncRoles(): void
    {
        $this->roles = collect();

        foreach (Role::cases() as $role) {
            $this->roles->put(
                $role->value,
                SpatieRole::firstOrCreate(['name' => $role->value])
            );
        }
    }

    /**
     * Sync permissions from the Permission enum to the database.
     */
    protected function syncPermissions(): void
    {
        $this->permissions = collect();

        foreach (Permission::cases() as $permission) {
            $this->permissions->put(
                $permission->value,
                SpatiePermission::firstOrCreate(['name' => $permission->value])
            );
        }
    }

    /**
     * Assign permissions to roles based on the Role enum's permissions() method.
     */
    protected function assignPermissionsToRoles(): void
    {
        foreach (Role::cases() as $role) {
            $permissionValues = array_map(
                fn (Permission $permission) => $permission->value,
                $role->permissions(),
            );

            $this->roles->get($role->value)->syncPermissions($permissionValues);
        }
    }

    /**
     * Get all roles from the database.
     */
    public function getRoles(): Collection
    {
        return SpatieRole::all();
    }

    /**
     * Get all permissions from the database.
     */
    public function getPermissions(): Collection
    {
        return SpatiePermission::all();
    }

    /**
     * Get a specific role by enum case.
     */
    public function getRole(Role $role): ?SpatieRole
    {
        return SpatieRole::findByName($role->value);
    }

    /**
     * Get a specific permission by enum case.
     */
    public function getPermission(Permission $permission): ?SpatiePermission
    {
        return SpatiePermission::findByName($permission->value);
    }
}
