<?php

namespace App\Enums\Concerns;

use Illuminate\Support\Collection;

trait Enum
{
    public static function values(): Collection
    {
        return collect(self::cases())->map(fn ($case): string => $case->value);
    }

    public static function only(array $cases, bool $asArray = false): Collection
    {
        return collect(self::cases())->filter(fn ($case) => in_array($case, $cases))->map(fn ($case) => $asArray ? $case->value : $case);
    }
}
