<?php

namespace App\Filament\Resources\DashboardResource\Widgets;

use App\Models\Transaction;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class CardTotal extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        return [
            Stat::make('Total Users', User::count())
                ->description('Jumlah Seluruh Pengguna')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),

            Stat::make('Total Transaksi', Transaction::count())
                ->description('Jumlah Transaksi berhasil')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('info'),

            Stat::make('Total Pendapatan', 'Rp ' . number_format(Transaction::sum('grand_total'), 0, ',', '.'))
                ->description('Pendapatan Keseluruhan Transaksi')
                ->descriptionIcon('heroicon-m-currency-dollar')
                ->color('warning'),
        ];
    }
}
