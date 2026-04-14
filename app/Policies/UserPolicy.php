<?php

namespace App\Policies;

use App\Enums\Role;
use App\Models\User;

class UserPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole([Role::SUPER, Role::ADMIN]);
    }

    public function view(User $user, User $model): bool
    {
        return $user->hasRole([Role::SUPER, Role::ADMIN]);
    }

    public function create(User $user): bool
    {
        return $user->hasRole([Role::SUPER, Role::ADMIN]);
    }

    public function update(User $user, User $model): bool
    {
        if ($model->hasRole(Role::SUPER) && ! $user->hasRole(Role::SUPER)) {
            return false;
        }

        return $user->hasRole([Role::SUPER, Role::ADMIN]);
    }

    public function delete(User $user, User $model): bool
    {
        if ($user->is($model)) {
            return false;
        }

        if ($model->hasRole(Role::SUPER) && ! $user->hasRole(Role::SUPER)) {
            return false;
        }

        return $user->hasRole([Role::SUPER, Role::ADMIN]);
    }

    public function restore(User $user, User $model): bool
    {
        return $user->hasRole(Role::SUPER);
    }

    public function suspend(User $user, User $model): bool
    {
        return $this->canManageSuspension($user, $model);
    }

    public function reactivate(User $user, User $model): bool
    {
        return $this->canManageSuspension($user, $model);
    }

    public function forceDelete(User $user, User $model): bool
    {
        return $user->hasRole(Role::SUPER);
    }

    protected function canManageSuspension(User $user, User $model): bool
    {
        if (! $model->hasRole(Role::USER)) {
            return false;
        }

        return $user->hasRole([Role::SUPER, Role::ADMIN]);
    }
}
