<?php

namespace App\Filament\Widgets;

use App\Models\User;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Date;

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
        $startDate = Date::now()->subDays($days - 1)->startOfDay();

        $registrations = User::query()
            ->where('created_at', '>=', $startDate)
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->pluck('count', 'date');

        $labels = [];
        $counts = [];

        for ($i = $days - 1; $i >= 0; $i--) {
            $date = Date::now()->subDays($i);
            $labels[] = $date->format('M d');
            $counts[] = $registrations[$date->toDateString()] ?? 0;
        }

        return [
            'labels' => $labels,
            'counts' => $counts,
        ];
    }
}
