<?php

namespace App\Enums;

use Illuminate\Support\Collection;

trait Enum
{
    public static function values(): Collection
    {
        return collect(self::cases())->map(fn ($case): string => $case->value);
    }
}
