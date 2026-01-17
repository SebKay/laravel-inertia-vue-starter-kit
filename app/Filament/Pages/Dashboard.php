<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\UserActivityChart;
use App\Filament\Widgets\UserStats;
use Filament\Support\Icons\Heroicon;

class Dashboard extends \Filament\Pages\Dashboard
{
    protected static string|\BackedEnum|null $navigationIcon = Heroicon::Home;

    public function getColumns(): int|array
    {
        return 6;
    }

    public function getWidgets(): array
    {
        return [
            UserStats::class,
            UserActivityChart::class,
        ];
    }
}
