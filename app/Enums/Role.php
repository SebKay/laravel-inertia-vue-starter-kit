<?php

namespace App\Enums;

use App\Enums\Concerns\Enum;

enum Role: string
{
    use Enum;

    case SUPER_ADMIN = 'super-admin';
    case ADMIN = 'admin';
    case USER = 'user';

    /**
     * Get the permissions assigned to this role.
     *
     * @return array<Permission>
     */
    public function permissions(): array
    {
        return match ($this) {
            self::SUPER_ADMIN => [
                Permission::ACCESS_ADMIN,
                Permission::CREATE_POSTS,
                Permission::VIEW_POSTS,
                Permission::EDIT_POSTS,
                Permission::UPDATE_POSTS,
                Permission::DELETE_POSTS,
            ],
            self::ADMIN => [
                Permission::CREATE_POSTS,
                Permission::VIEW_POSTS,
                Permission::EDIT_POSTS,
                Permission::UPDATE_POSTS,
                Permission::DELETE_POSTS,
            ],
            self::USER => [
                Permission::CREATE_POSTS,
                Permission::VIEW_POSTS,
                Permission::EDIT_POSTS,
                Permission::UPDATE_POSTS,
                Permission::DELETE_POSTS,
            ],
        };
    }
}
