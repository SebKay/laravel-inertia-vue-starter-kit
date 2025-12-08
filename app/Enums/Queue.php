<?php

namespace App\Enums;

use App\Enums\Concerns\Enum;

enum Queue: string
{
    use Enum;

    case DEFAULT = 'default';
    case MAIL = 'mail';
}
