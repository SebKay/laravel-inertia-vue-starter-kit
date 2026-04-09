<?php

namespace App\Enums;

use App\Enums\Concerns\Enum;
use Filament\Support\Contracts\HasLabel;

enum Role: string implements HasLabel
{
    use Enum;

    case SUPER = 'super';
    case ADMIN = 'admin';
    case USER = 'user';

    /**
     * @return array<Permission>
     */
    public function permissions(): array
    {
        return match ($this) {
            self::SUPER => [
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
            ],
            self::USER => [
                Permission::VIEW_POSTS,
            ],
        };
    }

    public function getLabel(): string
    {
        return match ($this) {
            self::SUPER => 'Super',
            self::ADMIN => 'Admin',
            self::USER => 'User',
        };
    }
}
