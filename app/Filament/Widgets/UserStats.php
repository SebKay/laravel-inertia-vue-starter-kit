<?php

namespace App\Filament\Widgets;

use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class UserStats extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Users', User::query()->count()),
            Stat::make('New users (last 7 days)', User::query()->where('created_at', '>=', now()->subDays(7))->count()),
            Stat::make('New users (last 30 days)', User::query()->where('created_at', '>=', now()->subDays(30))->count()),
        ];
    }
}
