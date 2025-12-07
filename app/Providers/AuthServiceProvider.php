<?php

namespace App\Providers;

use Spatie\Permission\Models\Role;
use App\Policies\RolePolicy;
use Spatie\Permission\Models\Permission;
use App\Policies\PermissionPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Role::class => RolePolicy::class,
        Permission::class => PermissionPolicy::class,
    ];
}
