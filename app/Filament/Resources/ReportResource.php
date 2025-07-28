<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReportResource\Pages;
use App\Filament\Resources\ReportResource\RelationManagers;
use App\Models\Transaction; // Correct model to use
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
// For export (these imports are generally for the resource itself, not the page actions)
use Filament\Actions\ExportAction; // Keep if you use it elsewhere, otherwise not strictly needed here
use Filament\Actions\Exports\ExportColumn; // Keep if you use it elsewhere, otherwise not strictly needed here
use Filament\Actions\Exports\ExcelExport; // Keep if you use it elsewhere, otherwise not strictly needed here
use Filament\Actions\Exports\PdfExport; // Keep if you use it elsewhere, otherwise not strictly needed here

// For PDF export, assuming barryvdh/laravel-dompdf is installed
use Barryvdh\DomPDF\Facade\Pdf;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

// Import TransactionExport for Excel export
use App\Exports\TransactionExport;

class ReportResource extends Resource
{
    protected static ?string $model = Transaction::class; // Use Transaction model

    protected static ?int $navigationSort = 4;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?string $navigationLabel = 'Laporan';

    protected static ?string $navigationGroup = 'Transaksi Management';

    protected static ?string $pluralModelLabel = 'Laporan Penjualan'; // More accurate label

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // No form for reports
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('trx_id')
                    ->label('ID Transaksi')
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
                    ->getStateUsing(fn(Transaction $record): int => $record->items->sum('quantity'))
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
                    ->sortable()
                    ->searchable(),
                TextColumn::make('payment_status')
                    ->label('Status Pembayaran')
                    ->badge()
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'settlement' => 'Success',
                        'capture' => 'Success',
                        'success' => 'Success',
                        'pending' => 'Pending',
                        'challenge' => 'Challenge',
                        'deny' => 'Ditolak',
                        'expire' => 'Kedaluwarsa',
                        'cancel' => 'Dibatalkan',
                        'refund' => 'Dikembalikan',
                        'partial_refund' => 'Pengembalian Sebagian',
                        'authorize' => 'Diotorisasi',
                        default => ucfirst($state),
                    })
                    ->color(fn(string $state): string => match ($state) {
                        'pending' => 'warning',
                        'settlement' => 'success',
                        'capture' => 'success',
                        'success' => 'success',
                        'challenge' => 'warning',
                        'deny' => 'danger',
                        'expire' => 'danger',
                        'cancel' => 'danger',
                        'refund' => 'danger',
                        'partial_refund' => 'danger',
                        'authorize' => 'info',
                        default => 'gray',
                    })
                    ->sortable()
                    ->searchable(),
                TextColumn::make('payment_type')
                    ->label('Tipe Pembayaran')
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
                    ])
                    ->label('Tipe Pembayaran'),
            ])
            ->headerActions([
                Action::make('export_excel')
                    ->label('Export Excel')
                    ->tooltip('Download laporan transaksi dalam format Excel (XLSX)')
                    ->color('success')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->action(function (Table $table): BinaryFileResponse {
                        $query = $table->getLivewire()->getFilteredTableQuery();

                        return \Maatwebsite\Excel\Facades\Excel::download(
                            new TransactionExport($query),
                            'laporan_transaksi_FaaRoti' . now()->format('Y-m-d_His') . '.xlsx'
                        );
                    }),
                // START: New PDF Export Action
                Action::make('export_pdf')
                    ->label('Export PDF')
                    ->tooltip('Download laporan transaksi dalam format PDF')
                    ->color('danger') // Warna merah untuk PDF
                    ->icon('heroicon-o-document-arrow-down') // Icon untuk PDF
                    ->action(function (Table $table): BinaryFileResponse {
                        // Dapatkan query yang sudah difilter dari tabel Filament
                        $query = $table->getLivewire()->getFilteredTableQuery();

                        // Ambil data transaksi dengan relasi yang dibutuhkan untuk PDF
                        $transactions = $query->with(['user'])->get();

                        // Hitung total pendapatan dari query yang sama
                        $totalGrandAmount = $query->sum('grand_total');

                        // Load view untuk PDF dan kirim data
                        $pdf = Pdf::loadView('exports.transactions_pdf', compact('transactions', 'totalGrandAmount'))
                            ->setPaper('a4', 'landscape');

                        // Simpan PDF ke file sementara
                        $tmpFile = tempnam(sys_get_temp_dir(), 'pdf');
                        file_put_contents($tmpFile, $pdf->output());

                        // Unduh file PDF sebagai BinaryFileResponse
                        return response()->download(
                            $tmpFile,
                            'laporan_transaksi_FaaRoti_' . now()->format('Y-m-d_His') . '.pdf'
                        )->deleteFileAfterSend(true);
                    }),
                // END: New PDF Export Action
            ])
            ->actions([
                // No actions for individual records in a report, as per request
            ])
            ->bulkActions([
                // No bulk actions for reports, as per request
            ]);
    }

    public static function getRelations(): array
    {
        return [
            // No relations for reports
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListReports::route('/'),
        ];
    }

    public static function canCreate(): bool
    {
        return false; // Disable create button
    }

    // Menambahkan eager loading untuk relasi 'items' saat mengambil data untuk ekspor
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with('items');
    }
}
