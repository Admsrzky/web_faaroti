<?php

namespace App\Filament\Resources\ProductResource\Widgets;

use App\Models\Product;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class ProductOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Produk', Product::count())
                ->description('Jumlah Keseluruhan Produk')
                ->descriptionIcon('heroicon-o-shopping-bag')
                ->color('primary'),

            Stat::make('Produk Stok Rendah', Product::where('stok', '<', 5)->count())
                ->description('Produk Stok kurang dari 3')
                ->descriptionIcon('heroicon-o-exclamation-triangle')
                ->color('danger'),

            Stat::make('Produk Populer', Product::where('is_popular', true)->count())
                ->description('Jumlah Produk Populer')
                ->descriptionIcon('heroicon-o-star')
                ->color('success'),

            Stat::make('Produk Tidak Populer', Product::where('is_popular', false)->count())
                ->description('Jumlah Produk Tidak Populer')
                ->descriptionIcon('heroicon-o-x-circle')
                ->color('info'),
        ];
    }
}
