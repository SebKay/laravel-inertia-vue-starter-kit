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
            DB::transaction(function () {
                $roleAssignments = $this->snapshotRoleAssignments();

                $this->truncate();

                $this->syncRoles();
                $this->syncPermissions();
                $this->assignPermissionsToRoles();
                $this->restoreRoleAssignments($roleAssignments);
            });

            $this->permissionRegistrar->forgetCachedPermissions();

            return;
        }

        $this->syncRoles();
        $this->syncPermissions();
        $this->assignPermissionsToRoles();

        $this->permissionRegistrar->forgetCachedPermissions();
    }

    /**
     * Snapshot current model-role assignments so a fresh sync can restore them.
     *
     * @return Collection<int, array{model_type: string, model_id: int|string, roles: array<int, array{name: string, guard_name: string|null}>}>
     */
    protected function snapshotRoleAssignments(): Collection
    {
        $tableNames = config('permission.table_names');

        /** @var Collection<int, object{model_type: string, model_id: int|string, name: string, guard_name: string|null}> $rows */
        $rows = DB::table($tableNames['model_has_roles'].' as model_has_roles')
            ->join($tableNames['roles'].' as roles', 'model_has_roles.role_id', '=', 'roles.id')
            ->select([
                'model_has_roles.model_type',
                'model_has_roles.model_id',
                'roles.name',
                'roles.guard_name',
            ])
            ->orderBy('model_has_roles.model_type')
            ->orderBy('model_has_roles.model_id')
            ->get();

        return $rows
            ->groupBy(fn (object $row) => $row->model_type.'|'.$row->model_id)
            ->map(function (Collection $group) {
                /** @var object{model_type: string, model_id: int|string} $first */
                $first = $group->first();

                return [
                    'model_type' => $first->model_type,
                    'model_id' => $first->model_id,
                    'roles' => $group
                        ->map(fn (object $row) => ['name' => $row->name, 'guard_name' => $row->guard_name])
                        ->values()
                        ->all(),
                ];
            })
            ->values();
    }

    /**
     * Restore model-role assignments after a fresh sync.
     *
     * @param  Collection<int, array{model_type: string, model_id: int|string, roles: array<int, array{name: string, guard_name: string|null}>}>  $roleAssignments
     */
    protected function restoreRoleAssignments(Collection $roleAssignments): void
    {
        if ($roleAssignments->isEmpty()) {
            return;
        }

        $tableNames = config('permission.table_names');

        $inserts = [];

        foreach ($roleAssignments as $assignment) {
            foreach ($assignment['roles'] as $roleSnapshot) {
                $role = $this->findRoleByNameAndGuard(
                    name: $roleSnapshot['name'],
                    guardName: $roleSnapshot['guard_name'],
                );

                if (! $role) {
                    continue;
                }

                $inserts[] = [
                    'role_id' => $role->id,
                    'model_type' => $assignment['model_type'],
                    'model_id' => $assignment['model_id'],
                ];
            }
        }

        if ($inserts === []) {
            return;
        }

        DB::table($tableNames['model_has_roles'])->insert($inserts);
    }

    protected function findRoleByNameAndGuard(string $name, ?string $guardName): ?SpatieRole
    {
        try {
            return $guardName
                ? SpatieRole::findByName($name, $guardName)
                : SpatieRole::findByName($name);
        } catch (\Throwable) {
            return null;
        }
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
