<?php

namespace App\Filament\Widgets;

use App\Models\User;
use Filament\Widgets\ChartWidget;

class UserActivityChart extends ChartWidget
{
    protected ?bool $isPolling = false;

    protected int|string|array $columnSpan = 3;

    protected ?string $heading = 'User Registrations';

    protected function getData(): array
    {
        $data = $this->getUserRegistrationsPerDay();

        return [
            'datasets' => [
                [
                    'label' => 'New Users',
                    'data' => $data['counts'],
                    'borderColor' => 'rgb(59, 130, 246)',
                    'backgroundColor' => 'rgba(59, 130, 246, 0.1)',
                    'fill' => true,
                    'tension' => 0.3,
                ],
            ],
            'labels' => $data['labels'],
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getUserRegistrationsPerDay(): array
    {
        $days = 30;
        $labels = [];
        $counts = [];

        for ($i = $days - 1; $i >= 0; $i--) {
            $date = \Illuminate\Support\Facades\Date::now()->subDays($i);
            $labels[] = $date->format('M d');

            $counts[] = User::query()
                ->whereDate('created_at', $date->toDateString())
                ->count();
        }

        return [
            'labels' => $labels,
            'counts' => $counts,
        ];
    }
}
