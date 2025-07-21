<?php

namespace App\Filament\Resources\DashboardResource\Widgets;

use App\Models\Transaction;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ChartPenjualan extends ChartWidget
{
    protected static ?string $heading = 'Penjualan Bulanan';

    protected static ?int $sort = 2;

    protected int|string|array $columnSpan = '50%';

    protected function getType(): string
    {
        return 'line';
    }

    protected function getData(): array
    {
        $months = collect([]);
        $sales = collect([]);

        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $months->push($month->format('M Y'));

            $monthlySales = Transaction::whereMonth('created_at', $month->month)
                ->whereYear('created_at', $month->year)
                ->sum('grand_total');

            $sales->push($monthlySales);
        }

        return [
            'datasets' => [
                [
                    'label' => 'Total Penjualan',
                    'data' => $sales->all(),
                    'borderColor' => '#0000ff',
                    'backgroundColor' => 'rgba(106, 90, 205, 0.2)',
                    'fill' => true
                ],
            ],
            'labels' => $months->all(),
        ];
    }

    protected function getOptions(): array
    {
        return [
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                ],
            ],
            'responsive' => true,
            'maintainAspectRatio' => false,
            'plugins' => [
                'legend' => [
                    'display' => true,
                    'position' => 'bottom',
                ]
            ]
        ];
    }
}
