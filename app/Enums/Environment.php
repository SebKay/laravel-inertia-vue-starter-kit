<?php

namespace App\Enums;

use App\Enums\Concerns\Enum;

enum Environment: string
{
    use Enum;

    case LOCAL = 'local';
    case TESTING = 'testing';
    case PRODUCTION = 'production';
    case STAGING = 'staging';
}
