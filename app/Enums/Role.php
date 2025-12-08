<?php

namespace App\Enums;

enum Role: string
{
    use Enum;

    case SUPER_ADMIN = 'super-admin';
    case ADMIN = 'admin';
    case USER = 'user';
}
