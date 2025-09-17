<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TransactionResource\Pages;
use App\Filament\Resources\TransactionResource\RelationManagers;
use App\Models\Transaction;
use App\Models\TransactionItem; // Import model TransactionItem
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn; // Untuk menampilkan gambar produk
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Select; // Untuk form Select di modal
use Filament\Forms\Components\Textarea; // Untuk catatan opsional di modal
use Filament\Notifications\Notification; // Untuk notifikasi sukses/gagal

class TransactionResource extends Resource
{
    protected static ?string $model = Transaction::class;

    protected static ?int $newPendingOrdersCount = null;

    protected static ?int $navigationSort = 3;

    protected static ?string $navigationIcon = 'heroicon-o-receipt-percent';

    protected static ?string $navigationLabel = 'Transaksi Penjualan';

    protected static ?string $navigationGroup = 'Transaksi Management';

    protected static ?string $pluralModelLabel = 'Transaksi Penjualan';

    // Menonaktifkan halaman Create dan Edit
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Form ini bisa dikosongkan atau digunakan untuk tampilan detail jika diperlukan
                // Kami akan menggunakan Action untuk menampilkan detail
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('trx_id')
                    ->label('ID Transaksi') // Label lebih deskriptif
                    ->sortable()
                    ->searchable(),
                TextColumn::make('name')
                    ->label('Nama Customer')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('grand_total')
                    ->money('IDR')
                    ->label('Total Transaksi')
                    ->sortable(),
                TextColumn::make('total_transaksi_produk')
                    ->label('Jumlah Pesanan')
                    ->getStateUsing(function (Transaction $record): int {
                        // KOREKSI: Gunakan 'quantity' karena itu adalah kolom di tabel transaction_items
                        return $record->items->sum('quantity');
                    })
                    ->sortable(),
                TextColumn::make('payment_type')
                    ->label('Tipe Pembayaran')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('status')
                    ->label('Status Pesanan')
                    ->badge()
                    ->formatStateUsing(fn(string $state): string => ucfirst($state)) // Hanya kapitalisasi
                    ->color(fn(string $state): string => match ($state) {
                        'pending' => 'warning',
                        'dikirim' => 'info',
                        'selesai' => 'success',
                        'dibatalkan' => 'danger',
                        default => 'gray',
                    })
                    ->sortable()
                    ->searchable(),
                TextColumn::make('payment_status')
                    ->label('Status Pembayaran')
                    ->badge() // Menampilkan sebagai badge
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'settlement' => 'Success', // Jika status 'settlement', tampilkan 'Success'
                        'capture' => 'Success',    // Jika status 'capture', tampilkan 'Success'
                        'success' => 'Success',    // Jika status 'success' (mungkin dari webhook), tampilkan 'Success'
                        'pending' => 'Pending',
                        'challenge' => 'Challenge',
                        'deny' => 'Ditolak',
                        'expire' => 'Kedaluwarsa',
                        'cancel' => 'Dibatalkan',
                        'refund' => 'Dikembalikan',
                        'partial_refund' => 'Pengembalian Sebagian',
                        'authorize' => 'Diotorisasi',
                        default => ucfirst($state), // Kapitalisasi status lain jika ada
                    })
                    ->color(fn(string $state): string => match ($state) {
                        'pending' => 'warning',    // Kuning untuk pending
                        'settlement' => 'success', // Hijau untuk settlement
                        'capture' => 'success',    // Hijau untuk capture
                        'success' => 'success',    // Hijau untuk success
                        'challenge' => 'warning',  // Kuning untuk challenge (masih menunggu)
                        'deny' => 'danger',        // Merah untuk ditolak
                        'expire' => 'danger',      // Merah untuk kedaluwarsa
                        'cancel' => 'danger',      // Merah untuk dibatalkan
                        'refund' => 'danger',      // Merah untuk refund
                        'partial_refund' => 'danger', // Merah untuk partial refund
                        'authorize' => 'info',     // Biru untuk diotorisasi (belum settlement)
                        default => 'gray',         // Abu-abu untuk status lainnya
                    })
                    ->sortable()
                    ->searchable(),
                TextColumn::make('created_at')
                    ->label('Tanggal Transaksi')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('tanggal_acara_month')
                    ->label('Bulan Acara')
                    ->options(function () {
                        $months = [];
                        for ($i = 1; $i <= 12; $i++) {
                            $months[$i] = \Carbon\Carbon::createFromDate(null, $i, 1)->locale('id')->monthName;
                        }
                        return $months;
                    })
                    ->query(function (\Illuminate\Database\Eloquent\Builder $query, array $data): \Illuminate\Database\Eloquent\Builder {
                        if (isset($data['value'])) {
                            $query->whereMonth('created_at', $data['value']);
                        }
                        return $query;
                    }),

                Tables\Filters\SelectFilter::make('tanggal_acara_year')
                    ->label('Tahun Acara')
                    ->options(function () {
                        $years = [];
                        $currentYear = \Carbon\Carbon::now()->year;
                        for ($i = $currentYear - 5; $i <= $currentYear + 5; $i++) {
                            $years[$i] = $i;
                        }
                        return $years;
                    })
                    ->query(function (\Illuminate\Database\Eloquent\Builder $query, array $data): \Illuminate\Database\Eloquent\Builder {
                        if (isset($data['value'])) {
                            $query->whereYear('created_at', $data['value']);
                        }
                        return $query;
                    }),

                SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'dikirim' => 'Dikirim',
                        'selesai' => 'Selesai',
                        'dibatalkan' => 'Dibatalkan',
                    ])
                    ->label('Status Pesanan'),
                SelectFilter::make('payment_status')
                    ->options([
                        'pending' => 'Pending',
                        'success' => 'Success',
                        'settlement' => 'Settlement',
                        'challenge' => 'Challenge',
                        'deny' => 'Ditolak',
                        'expire' => 'Kedaluwarsa',
                        'cancel' => 'Dibatalkan',
                        'refund' => 'Dikembalikan',
                        'partial_refund' => 'Pengembalian Sebagian',
                        'authorize' => 'Diotorisasi',
                    ])
                    ->label('Status Pembayaran'),
                SelectFilter::make('payment_type')
                    ->options([
                        'qris' => 'QRIS',
                        'bank_transfer' => 'Transfer Bank',
                        'credit_card' => 'Kartu Kredit',
                        'gopay' => 'GoPay',
                        // Tambahkan tipe pembayaran lain yang mungkin
                    ])
                    ->label('Tipe Pembayaran'),
            ])
            ->actions([
                // Action untuk melihat detail transaksi
                Tables\Actions\Action::make('viewDetails')
                    ->label('Lihat Detail')
                    ->icon('heroicon-o-eye')
                    ->modalHeading('Detail Transaksi')
                    ->modalSubmitAction(false) // Nonaktifkan tombol submit di modal
                    ->modalCancelActionLabel('Tutup') // Ubah teks tombol cancel
                    ->modalContent(function (Transaction $record) {
                        // Pastikan relasi 'items' dimuat
                        $record->load('items.product');

                        return view('Filament.Resources.TransactionResource.Modals.view-transaction-details', [
                            'transaction' => $record,
                        ]);
                    })
                    ->slideOver(), // Tampilkan modal sebagai slide-over

                // Action untuk mengubah status pesanan
                Tables\Actions\Action::make('changeStatus')
                    ->label('Ubah Status')
                    ->icon('heroicon-o-pencil-square')
                    ->form([
                        Select::make('status')
                            ->label('Status Pesanan Baru')
                            ->options([
                                'pending' => 'Pending',
                                'dikirim' => 'Dikirim',
                                'selesai' => 'Selesai',
                                'dibatalkan' => 'Dibatalkan',
                            ])
                            ->default(fn(Transaction $record) => $record->status) // Default ke status saat ini
                            ->required(),
                        Textarea::make('notes')
                            ->label('Catatan (Opsional)')
                            ->placeholder('Tambahkan catatan perubahan status'),
                    ])
                    ->action(function (Transaction $record, array $data): void {
                        $record->status = $data['status'];
                        // Anda bisa menyimpan catatan di kolom terpisah jika ada,
                        // atau menambahkannya ke log aktivitas.
                        $record->save();

                        Notification::make()
                            ->title('Status pesanan berhasil diubah!')
                            ->success()
                            ->send();
                    })
                    ->modalWidth('md'), // Atur lebar modal
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            // Jika Anda memiliki relation manager untuk item transaksi, bisa ditambahkan di sini
            // RelationManagers\TransactionItemsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTransactions::route('/'),
            // Hapus 'create' dan 'edit' jika Anda tidak ingin mengizinkan pembuatan/pengeditan langsung
            // 'create' => Pages\CreateTransaction::route('/create'),
            // 'edit' => Pages\EditTransaction::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        // Check if the count has already been calculated for this request.
        if (is_null(static::$newPendingOrdersCount)) {
            // Calculate and cache the count of transactions that are 'pending' AND created today.
            static::$newPendingOrdersCount = static::getModel()::where('status', 'pending')
                ->whereDate('created_at', today())
                ->count();
        }

        // Return the count as a string if it's greater than 0, otherwise return null.
        return static::$newPendingOrdersCount > 0 ? (string) static::$newPendingOrdersCount : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        // Check the cached count to determine the color.
        if (static::$newPendingOrdersCount > 0) {
            return 'success'; // Warna hijau untuk pesanan baru
        }

        return null;
    }

    // Menonaktifkan tombol 'Create' di halaman daftar
    public static function canCreate(): bool
    {
        return false;
    }

    // Menonaktifkan tombol 'Edit' di halaman daftar (jika Anda tidak ingin ada edit action di baris)
    // Jika Anda ingin edit action di baris tapi tidak di halaman terpisah, jangan tambahkan ini
    // public static function canEdit(Model $record): bool
    // {
    //     return false;
    // }
}
