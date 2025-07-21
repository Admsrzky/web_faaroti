<?php

namespace App\Filament\Resources\TransactionResource\Widgets;

use App\Models\Transaction;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;


class TransactionOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $totalTransactions = Transaction::count();
        $pendingTransactions = Transaction::where('status', 'pending')->count();
        $completedTransactions = Transaction::where('status', 'selesai')->count();
        $cancelledTransactions = Transaction::where('status', 'dibatalkan')->count();

        return [
            Stat::make('Total Transaksi', $totalTransactions)
                ->description('Jumlah keseluruhan pesanan')
                ->descriptionIcon('heroicon-o-arrow-trending-up')
                ->color('info'), // Warna biru
            Stat::make('Transaksi Pending', $pendingTransactions)
                ->description('Pesanan yang belum diproses')
                ->descriptionIcon('heroicon-o-exclamation-triangle')
                ->color('warning'), // Warna kuning
            Stat::make('Transaksi Selesai', $completedTransactions)
                ->description('Pesanan yang sudah selesai')
                ->descriptionIcon('heroicon-o-check-circle')
                ->color('success'), // Warna hijau
            Stat::make('Transaksi Dibatalkan', $cancelledTransactions)
                ->description('Pesanan yang dibatalkan')
                ->descriptionIcon('heroicon-o-x-circle')
                ->color('danger'), // Warna merah
        ];
    }
}
