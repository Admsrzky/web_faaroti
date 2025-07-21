<?php

namespace App\Filament\Resources\DashboardResource\Widgets;

use App\Models\Transaction; // Import model Transaction
use Filament\Widgets\ChartWidget;
use Carbon\Carbon; // Untuk manipulasi tanggal

class RiwayatPenjualan extends ChartWidget
{
    protected static ?string $heading = 'Riwayat Penjualan Harian'; // Judul untuk widget chart
    protected static ?int $sort = 4; // Atur urutan widget di dashboard, setelah stats overview
    protected static ?string $maxHeight = '300px'; // Batasi tinggi chart

    protected function getType(): string
    {
        return 'line'; // Menampilkan chart dalam bentuk garis
    }

    protected function getData(): array
    {
        // Ambil data transaksi dari 30 hari terakhir
        $transactions = Transaction::query()
            ->selectRaw('DATE(created_at) as date, SUM(grand_total) as total_sales')
            ->where('created_at', '>=', Carbon::now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Siapkan data untuk chart
        $labels = [];
        $data = [];

        // Inisialisasi data untuk 30 hari terakhir, dengan nilai 0 jika tidak ada penjualan
        for ($i = 29; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');
            $labels[] = Carbon::parse($date)->format('d M'); // Format label tanggal (contoh: 15 Jul)
            $data[$date] = 0; // Default 0
        }

        // Isi data penjualan yang sebenarnya
        foreach ($transactions as $transaction) {
            $data[$transaction->date] = (float) $transaction->total_sales;
        }

        return [
            'labels' => array_values($labels),
            'datasets' => [
                [
                    'label' => 'Total Penjualan (IDR)',
                    'data' => array_values($data),
                    'backgroundColor' => 'rgba(76, 175, 80, 0.2)', // Warna latar belakang area chart (hijau transparan)
                    'borderColor' => '#4CAF50', // Warna garis chart (hijau)
                    'fill' => true, // Mengisi area di bawah garis
                    'tension' => 0.4, // Kehalusan garis
                ],
            ],
        ];
    }

    protected function getOptions(): array
    {
        return [
            'scales' => [
                'y' => [
                    'beginAtZero' => true, // Memulai sumbu Y dari nol
                    'ticks' => [
                        'callback' => 'function(value) { return "Rp " + value.toLocaleString("id-ID"); }', // Format label sumbu Y sebagai Rupiah
                    ],
                ],
            ],
            'plugins' => [
                'legend' => [
                    'display' => true, // Menampilkan legenda
                ],
            ],
            'responsive' => true,
            'maintainAspectRatio' => false, // Penting untuk kontrol tinggi dengan maxHeight
        ];
    }
}
