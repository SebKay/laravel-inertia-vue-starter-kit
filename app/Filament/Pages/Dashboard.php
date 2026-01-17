<?php

namespace App\Filament\Pages;

use Filament\Support\Icons\Heroicon;

class Dashboard extends \Filament\Pages\Dashboard
{
    protected static string|\BackedEnum|null $navigationIcon = Heroicon::Home;
}
