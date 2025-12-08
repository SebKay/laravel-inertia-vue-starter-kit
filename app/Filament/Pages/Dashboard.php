<?php

namespace App\Filament\Pages;

use CodeWithDennis\FilamentLucideIcons\Enums\LucideIcon;

class Dashboard extends \Filament\Pages\Dashboard
{
    protected static string|\BackedEnum|null $navigationIcon = LucideIcon::House;
}
