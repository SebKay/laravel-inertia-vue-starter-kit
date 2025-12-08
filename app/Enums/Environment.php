<?php

namespace App\Enums;

enum Environment: string
{
    use Enum;

    case LOCAL = 'local';
    case TESTING = 'testing';
    case PRODUCTION = 'production';
    case STAGING = 'staging';
}
