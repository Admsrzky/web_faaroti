<!DOCTYPE html>
<html>

<head>
    <title>Laporan Transaksi</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 9pt;
            /* Mengurangi font size sedikit */
            margin: 15mm;
            /* Mengurangi margin halaman untuk lebih banyak ruang */
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            table-layout: fixed;
            /* Penting: Memaksa tabel untuk mengikuti lebar kolom yang ditentukan */
        }

        th,
        td {
            border: 1px solid #e0e0e0;
            padding: 7px;
            /* Mengurangi padding */
            text-align: left;
            word-wrap: break-word;
            /* Memecah kata panjang */
        }

        th {
            background-color: #4CAF50;
            color: white;
            text-align: center;
            font-size: 10pt;
            /* Mengurangi font size header */
            text-transform: uppercase;
        }

        .total-row td {
            background-color: #D9EAD3;
            font-weight: bold;
            font-size: 9.5pt;
            /* Menyesuaikan ukuran font total row */
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .title {
            text-align: center;
            margin-bottom: 30px;
            font-size: 16pt;
            /* Mengurangi ukuran font judul */
            font-weight: bold;
            color: #333;
        }

        .signature-block {
            width: 300px;
            margin-left: auto;
            margin-right: 0;
            text-align: center;
            margin-top: 50px;
        }

        .signature-block p {
            margin: 5px 0;
        }

        .signature-line {
            border-bottom: 1px solid #000;
            width: 200px;
            margin: 50px auto 10px auto;
        }

        /* Penyesuaian lebar kolom spesifik (opsional, bisa disesuaikan lagi) */
        /* Anda bisa menambahkan kelas pada <th> dan <td> untuk memberikan lebar spesifik */
        /* Contoh: */
        /* th:nth-child(1), td:nth-child(1) { width: 5%; } /* ID Transaksi */
        /* th:nth-child(2), td:nth-child(2) { width: 10%; } /* TRX ID */
        /* th:nth-child(3), td:nth-child(3) { width: 12%; } /* Nama Pelanggan */
        /* th:nth-child(4), td:nth-child(4) { width: 20%; } /* Alamat */
        /* th:nth-child(5), td:nth-child(5) { width: 5%; } /* Qty */
        /* th:nth-child(6), td:nth-child(6) { width: 8%; } /* Sub Total */
        /* th:nth-child(7), td:nth-child(7) { width: 8%; } /* Grand Total */
        /* th:nth-child(8), td:nth-child(8) { width: 8%; } /* Status Kirim */
        /* th:nth-child(9), td:nth-child(9) { width: 8%; } /* Metode Bayar */
        /* th:nth-child(10), td:nth-child(10) { width: 8%; } /* Status Bayar */
        /* th:nth-child(11), td:nth-child(11) { width: 9%; } /* Tgl Dibuat */
        /* th:nth-child(12), td:nth-child(12) { width: 9%; } /* Tgl Diperbarui */

        /* Jika masih terpotong, pertimbangkan untuk menghilangkan beberapa kolom yang kurang krusial */
        /* atau membuat orientasi halaman menjadi landscape jika memungkinkan di konfigurasi dompdf */
    </style>
</head>

<body>
    <div class="title">Laporan Penjualan Faa Roti {{ \Carbon\Carbon::now()->format('d-m-Y') }}</div>

    <table>
        <thead>
            <tr>
                <th>ID Transaksi</th>
                <th>TRX ID</th>
                <th>Nama Pelanggan</th>
                <th>Qty</th>
                <th>Sub Total</th>
                <th>Grand Total</th>
                <th>Status Kirim</th>
                <th>Metode Bayar</th>
                <th>Status Bayar</th>
                <th>Tgl Dibuat</th>
                <th>Tgl Diperbarui</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($transactions as $transaction)
                <tr>
                    <td class="text-center">{{ $transaction->id }}</td>
                    <td>{{ $transaction->trx_id }}</td>
                    <td>{{ $transaction->name }}</td>
                    <td class="text-center">{{ $transaction->total_quantity }}</td>
                    <td class="text-right">{{ number_format($transaction->total_sub_total, 2, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($transaction->grand_total, 2, ',', '.') }}</td>
                    <td class="text-center">{{ $transaction->status }}</td>
                    <td class="text-center">{{ $transaction->payment_type }}</td>
                    <td class="text-center">{{ $transaction->payment_status }}</td>
                    <td>{{ $transaction->created_at->format('d/m/Y H:i:s') }}</td>
                    <td>{{ $transaction->updated_at->format('d/m/Y H:i:s') }}</td>
                </tr>
            @endforeach
            <tr class="total-row">
                {{-- colspan disesuaikan dengan jumlah kolom yang ada (12 kolom) --}}
                <td colspan="10" class="text-right">Total Pendapatan:</td>
                <td colspan="2" class="text-left">{{ number_format($totalGrandAmount, 2, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    <div class="signature-block">
        <p>{{ \Carbon\Carbon::now()->format('d F Y') }},</p>
        <p>Hormat Kami,</p>
        <div class="signature-line"></div>
        <p><strong>Erni Anggraini</strong></p>
    </div>
</body>

</html>
