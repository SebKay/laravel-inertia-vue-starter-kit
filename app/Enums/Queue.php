<?php

namespace App\Enums;

enum Queue: string
{
    use Enum;

    case DEFAULT = 'default';
    case MAIL = 'mail';
}
