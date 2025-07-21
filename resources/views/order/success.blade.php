@extends('layouts.master')

@section('title', 'Pembayaran Berhasil!')
@section('description', 'Terima kasih! Pembayaran Anda telah berhasil diproses.')

@section('content')
    <div class="container py-32 mx-auto text-center animate__animated animate__fadeIn">
        <div class="max-w-2xl p-8 mx-auto bg-white rounded-lg shadow-xl md:p-12">
            <div class="mb-6">
                <i class="text-6xl text-green-500 fas fa-check-circle animate__animated animate__bounceIn"></i>
            </div>
            <h1 class="mb-4 text-4xl font-extrabold text-gray-800 md:text-5xl animate__animated animate__fadeInDown">
                Pembayaran Berhasil!</h1>
            <p class="mb-8 text-lg leading-relaxed text-gray-700 md:text-xl animate__animated animate__fadeInUp">
                Terima kasih atas pesanan Anda. Pembayaran Anda telah berhasil diproses.
                Kami akan segera menyiapkan pesanan Anda untuk pengiriman.
            </p>

            @if (isset($transaction) && $transaction)
                <div class="p-6 mb-8 text-left border border-gray-200 rounded-md bg-gray-50 animate__animated animate__fadeInUp"
                    data-wow-delay="0.2s">
                    <h3 class="mb-4 text-xl font-semibold text-gray-800">Detail Pesanan Anda:</h3>
                    <div class="grid grid-cols-1 gap-4 text-gray-700 md:grid-cols-2">
                        <div>
                            <p><strong>ID Transaksi:</strong> <span
                                    class="font-mono text-blue-700">{{ $transaction->trx_id }}</span></p>
                            <p><strong>Total Pembayaran:</strong> <span class="font-bold text-primary">Rp
                                    {{ number_format($transaction->grand_total, 0, ',', '.') }}</span></p>
                        </div>
                        <div>
                            <p><strong>Status Pembayaran:</strong>
                                @if ($transaction->payment_status == 'success' || $transaction->payment_status == 'settlement')
                                    <span class="font-semibold text-green-600">Berhasil</span>
                                @elseif ($transaction->payment_status == 'pending' || $transaction->payment_status == 'challenge')
                                    <span class="font-semibold text-yellow-600">Menunggu Pembayaran</span>
                                @else
                                    <span class="font-semibold text-red-600">Gagal/Dibatalkan</span>
                                @endif
                            </p>
                            <p><strong>Metode Pembayaran:</strong> <span
                                    class="capitalize">{{ $transaction->payment_type ?? 'N/A' }}</span></p>
                        </div>
                    </div>
                </div>
            @else
                <p class="mb-8 text-gray-600">Detail transaksi tidak tersedia saat ini, tetapi pembayaran Anda telah
                    berhasil.</p>
            @endif

            <div class="flex flex-col justify-center gap-4 md:flex-row animate__animated animate__fadeInUp"
                data-wow-delay="0.4s">
                <a href="{{ route('home') }}"
                    class="px-8 py-3 text-lg font-semibold text-white transition-colors duration-300 rounded-full shadow-lg bg-primary hover:bg-primary-dark">
                    Kembali ke Beranda
                </a>
                <a href="{{ route('order.history') }}"
                    class="px-8 py-3 text-lg font-semibold text-gray-800 transition-colors duration-300 bg-gray-200 rounded-full shadow-lg hover:bg-gray-300">
                    Lihat Riwayat Pesanan
                </a>
            </div>
        </div>
    </div>
@endsection
