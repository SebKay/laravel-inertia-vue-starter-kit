<?php

namespace App\Enums;

use App\Enums\Concerns\Enum;

enum Role: string
{
    use Enum;

    case SUPER_ADMIN = 'super-admin';
    case ADMIN = 'admin';
    case USER = 'user';
}
