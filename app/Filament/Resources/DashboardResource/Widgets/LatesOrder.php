<?php

namespace App\Filament\Resources\DashboardResource\Widgets;

use App\Models\Transaction; // Import model Transaction
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;

class RiwayatPemesanan extends BaseWidget
{
    protected static ?string $heading = 'Pesanan Terbaru'; // Judul untuk widget tabel
    protected static ?int $sort = 4; // Atur urutan widget di dashboard (setelah Riwayat Penjualan)
    protected static int $perPage = 5; // Batasi jumlah baris per halaman untuk tampilan dashboard
    protected int|string|array $columnSpan = 'full';



    public function table(Table $table): Table
    {
        return $table
            ->query(
                // Query untuk mengambil 5 pesanan terbaru
                Transaction::query()
                    ->orderBy('created_at', 'desc') // Urutkan berdasarkan tanggal terbaru
                    ->limit(static::$perPage) // Batasi jumlah data sesuai $perPage
            )
            ->columns([
                TextColumn::make('trx_id')
                    ->label('ID Transaksi')
                    ->sortable(),
                TextColumn::make('name')
                    ->label('Nama Pelanggan')
                    ->sortable(),
                TextColumn::make('grand_total')
                    ->label('Grand Total')
                    ->money('IDR') // Format sebagai mata uang Rupiah
                    ->sortable(),
                TextColumn::make('status')
                    ->label('Status Pesanan')
                    ->badge()
                    ->formatStateUsing(fn(string $state): string => ucfirst($state))
                    ->color(fn(string $state): string => match ($state) {
                        'pending' => 'warning',
                        'dikirim' => 'info',
                        'selesai' => 'success',
                        'dibatalkan' => 'danger',
                        default => 'gray',
                    })
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Tanggal Transaksi')
                    ->dateTime('d/m/Y H:i') // Format tanggal dan waktu
                    ->sortable(),
            ])
            ->actions([
                // Anda bisa menambahkan aksi untuk setiap baris di sini jika diperlukan
                // Contoh: Action::make('view')->url(fn (Transaction $record): string => ReportResource::getUrl('edit', ['record' => $record])),
            ])
            ->bulkActions([
                // Tidak ada aksi massal untuk widget ini
            ])
            ->paginated(false); // Nonaktifkan paginasi di bagian bawah tabel widget
    }
}
