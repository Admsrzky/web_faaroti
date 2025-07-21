<!DOCTYPE html>
<html>

<head>
    <title>Laporan Transaksi</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 10pt;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #4CAF50;
            /* Hijau gelap */
            color: white;
            text-align: center;
        }

        .total-row td {
            background-color: #D9EAD3;
            /* Hijau muda */
            font-weight: bold;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .title {
            text-align: center;
            margin-bottom: 20px;
            font-size: 14pt;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="title">Laporan Transaksi {{ \Carbon\Carbon::now()->format('d-m-Y') }}</div>

    <table>
        <thead>
            <tr>
                <th>ID Transaksi</th>
                <th>TRX ID</th>
                <th>Nama Pelanggan</th>
                <th>Email Pelanggan</th>
                <th>Telepon Pelanggan</th>
                <th>Kota Pelanggan</th>
                <th>Kode Pos</th>
                <th>Alamat</th>
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
                    <td>{{ $transaction->email }}</td>
                    <td>{{ $transaction->phone }}</td>
                    <td>{{ $transaction->city }}</td>
                    <td>{{ $transaction->post_code }}</td>
                    <td>{{ $transaction->address }}</td>
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
                <td colspan="10" class="text-right">Total Pendapatan:</td>
                <td colspan="6" class="text-left">{{ number_format($totalGrandAmount, 2, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>
</body>

</html>
