<?php

namespace App\Exports;

use App\Models\Transaction; // Menggunakan model Transaction
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithFooters; // New import for footers
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Reader\Xml\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border; // For borders
use PhpOffice\PhpSpreadsheet\Style\Fill;   // For fill type
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class TransactionExport implements
    FromQuery,
    WithHeadings,
    WithMapping,
    WithStyles,
    ShouldAutoSize,
    WithColumnFormatting,
    WithTitle
{
    protected ?Builder $query;
    protected ?float $totalGrandAmount = null; // Property to store the sum

    public function __construct(?Builder $query = null)
    {
        $this->query = $query;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        // Menggabungkan data dari tabel 'transactions' dengan 'users' (untuk nama dan email user)
        // dan 'promo_codes' jika ada relasi dan ingin menampilkan nama promo.
        // Asumsi ada relasi 'user' dan 'promoCode' di model Transaction.
        return $this->query ?? \App\Models\Transaction::query()
            ->with(['user', 'promoCode']);
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        // Sesuaikan dengan kolom-kolom yang ingin kamu tampilkan di Excel
        return [
            'ID Transaksi',              // A
            'TRX ID',                    // B
            'Nama Pelanggan',            // C
            'Email Pelanggan',           // D
            'Telepon Pelanggan',         // E
            'Kota Pelanggan',            // F
            'Kode Pos Pelanggan',        // G
            'Alamat Pelanggan',          // H
            'Total Kuantitas',           // I
            'Sub Total Amount',          // J
            'Grand Total Amount',        // K
            'Status Pengiriman',         // L
            'Metode Pembayaran',         // M
            'Status Pembayaran',         // N
            'Tanggal Dibuat',            // O (was Q)
            'Tanggal Diperbarui',        // P (was R)
        ];
    }

    /**
     * @param \App\Models\Transaction $transaction
     * @return array
     */
    public function map($transaction): array
    {
        // Mapping data dari setiap objek Transaction ke baris Excel
        return [
            $transaction->id,
            $transaction->trx_id,
            $transaction->name,
            $transaction->email,
            $transaction->phone,
            $transaction->city,
            $transaction->post_code,
            $transaction->address,
            $transaction->total_quantity,
            $transaction->total_sub_total,
            $transaction->grand_total,
            $transaction->status,
            $transaction->payment_type,
            $transaction->payment_status,
            $transaction->created_at,
            $transaction->updated_at,
        ];
    }

    /**
     * Optional: Apply styles to the worksheet.
     * @param Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet)
    {
        // Get the total number of rows including header and data
        $totalRows = $sheet->getHighestRow();

        return [
            // Style baris pertama (header)
            1 => [
                'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']], // Membuat teks header menjadi bold dan putih
                'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER], // Pusatkan teks header
                'fill' => [ // Warna latar belakang header
                    'fillType' => Fill::FILL_SOLID,
                    'color' => ['argb' => 'FF4CAF50'], // Hijau gelap (contoh)
                ],
                'borders' => [ // Tambahkan border ke header
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['argb' => 'FF000000'],
                    ],
                ],
            ],
            // Style untuk baris footer (baris terakhir)
            $totalRows => [
                'font' => ['bold' => true],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'color' => ['argb' => 'FFD9EAD3'], // Hijau muda
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['argb' => 'FF000000'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Optional: Define column formats.
     * @return array
     */
    public function columnFormats(): array
    {
        return [
            // Kolom J: Sub Total Amount (format mata uang)
            'J' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1, // Contoh: 1,234.00
            // Kolom K: Grand Total Amount (format mata uang)
            'K' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            // Kolom O: Tanggal Dibuat (format tanggal dan waktu)
            'O' => NumberFormat::FORMAT_DATE_DDMMYYYY . ' hh:mm:ss', // Contoh: 01/01/2025 14:30:00
            // Kolom P: Tanggal Diperbarui (format tanggal dan waktu)
            'P' => NumberFormat::FORMAT_DATE_DDMMYYYY . ' hh:mm:ss',
        ];
    }

    /**
     * Optional: Set the title of the worksheet.
     * @return string
     */
    public function title(): string
    {
        return 'Laporan Transaksi ' . now()->format('Y-m-d');
    }

    /**
     * Optional: Add a footer row.
     * @return array
     */
    public function footers(): array
    {
        // Calculate the sum of grand_total from the database
        // This query should match the base query in the query() method for consistency
        $sumQuery = $this->query ?? \App\Models\Transaction::query();
        $this->totalGrandAmount = $sumQuery->sum('grand_total');

        // Create an empty array for the footer row
        $footerRow = array_fill(0, count($this->headings()), '');

        // Place the total grand amount in the 'Grand Total Amount' column (K)
        // 'K' is index 10 (0-indexed) based on the current headings array
        $grandTotalColumnIndex = array_search('Grand Total Amount', $this->headings());
        if ($grandTotalColumnIndex !== false) {
            $footerRow[$grandTotalColumnIndex] = $this->totalGrandAmount;
        }

        // Add a label for the total in the column before Grand Total Amount
        if ($grandTotalColumnIndex > 0) {
            $footerRow[$grandTotalColumnIndex - 1] = 'Total Pendapatan:';
        }

        return [
            $footerRow
        ];
    }
}
