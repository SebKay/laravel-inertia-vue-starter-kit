<?php

namespace App\Enums;

use App\Enums\Concerns\Enum;

enum Permission: string
{
    use Enum;

    case ACCESS_ADMIN = 'access-filament';

    case CREATE_POSTS = 'create-posts';
    case VIEW_POSTS = 'view-posts';
    case EDIT_POSTS = 'edit-posts';
    case UPDATE_POSTS = 'update-posts';
    case DELETE_POSTS = 'delete-posts';
}
