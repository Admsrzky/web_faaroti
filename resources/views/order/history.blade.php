@extends('layouts.master')

@section('title', 'Riwayat Pesanan - Faa Roti')
@section('description', 'Lihat riwayat pesanan Anda di Faa Roti.')

@section('content')
    <div class="container px-4 py-32 mx-auto md:px-6">
        <div class="p-6 bg-white rounded-lg shadow-md animate__animated animate__fadeInUp" data-wow-delay="0.1s">
            <h2 class="mb-6 text-2xl font-bold text-gray-900">Daftar Pesanan Anda</h2>

            @forelse ($transactions as $transaction)
                <div
                    class="p-4 mb-6 transition-shadow duration-200 border border-gray-200 rounded-lg last:mb-0 bg-gray-50 hover:shadow-lg">
                    {{-- Header Ringkasan Pesanan (Selalu Terlihat) --}}
                    <div class="flex flex-col items-start justify-between cursor-pointer md:flex-row md:items-center toggle-details"
                        data-target="#order-details-{{ $transaction->id }}">
                        <h3 class="mb-2 text-xl font-semibold text-gray-800 md:mb-0">Order ID: <span
                                class="text-primary">{{ $transaction->trx_id }}</span></h3>
                        <div class="flex flex-col items-start gap-2 md:flex-row md:items-center">
                            <span class="text-sm text-gray-600">Tanggal Pesanan:
                                {{ $transaction->created_at->format('d M Y H:i') }}</span>
                            <span
                                class="px-3 py-1 rounded-full text-sm font-semibold
                                @if ($transaction->status == 'pending') bg-yellow-100 text-yellow-800
                                @elseif($transaction->status == 'dikirim') bg-blue-100 text-blue-800
                                @elseif($transaction->status == 'selesai') bg-green-100 text-green-800
                                @elseif($transaction->status == 'dibatalkan') bg-red-100 text-red-800
                                @else bg-gray-100 text-gray-800 @endif">
                                Status: {{ ucfirst($transaction->status) }}
                            </span>
                            <i class="ml-2 text-gray-500 transition-transform duration-300 fas fa-chevron-down"></i>
                        </div>
                    </div>

                    {{-- Detail Pesanan (Tersembunyi secara default) --}}
                    <div id="order-details-{{ $transaction->id }}"
                        class="hidden pt-4 mt-4 border-t border-gray-200 order-details-content">
                        <div class="grid grid-cols-1 gap-4 mb-4 md:grid-cols-2">
                            <div>
                                <p class="font-medium text-gray-700">Total Pembayaran:</p>
                                <p class="text-2xl font-bold text-primary">Rp
                                    {{ number_format($transaction->grand_total, 0, ',', '.') }}</p>
                            </div>
                            <div>
                                <p class="font-medium text-gray-700">Status Pembayaran:</p>
                                <span
                                    class="px-3 py-1 rounded-full text-sm font-semibold inline-block
                                    @if ($transaction->payment_status == 'pending') bg-yellow-100 text-yellow-800
                                    @elseif(
                                        $transaction->payment_status == 'success' ||
                                            $transaction->payment_status == 'settlement' ||
                                            $transaction->payment_status == 'capture') bg-green-100 text-green-800
                                    @elseif(
                                        $transaction->payment_status == 'deny' ||
                                            $transaction->payment_status == 'expire' ||
                                            $transaction->payment_status == 'cancel' ||
                                            $transaction->payment_status == 'refund') bg-red-100 text-red-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                    {{-- DIUBAH: Menampilkan 'Success' jika statusnya settlement/success/capture --}}
                                    @if (
                                        $transaction->payment_status == 'settlement' ||
                                            $transaction->payment_status == 'success' ||
                                            $transaction->payment_status == 'capture')
                                        Success
                                    @else
                                        {{ ucfirst($transaction->payment_status) }}
                                    @endif
                                </span>
                            </div>
                        </div>

                        <h4 class="mb-3 text-lg font-semibold text-gray-800">Detail Produk:</h4>
                        <div class="mb-4 space-y-3">
                            @forelse ($transaction->items as $item)
                                <div class="flex items-center p-3 bg-white border border-gray-100 rounded-md shadow-sm">
                                    @if ($item->product_photo)
                                        <img src="{{ asset('storage/' . $item->product_photo) }}" alt="Foto Produk"
                                            class="object-cover w-16 h-16 mr-4 border border-gray-200 rounded-md">
                                    @endif
                                    <div class="flex-1">
                                        <p class="font-medium text-gray-900">{{ $item->quantity }}x
                                            {{ $item->product_name }}</p>
                                        <p class="text-sm text-gray-600">Rp
                                            {{ number_format($item->price_at_purchase, 0, ',', '.') }} per item</p>
                                    </div>
                                    <p class="font-semibold text-gray-800">Rp
                                        {{ number_format($item->sub_total_item, 0, ',', '.') }}</p>
                                </div>
                            @empty
                                <p class="text-gray-600">Tidak ada detail produk untuk transaksi ini.</p>
                            @endforelse
                        </div>

                        <h4 class="mb-3 text-lg font-semibold text-gray-800">Informasi Pengiriman:</h4>
                        <div class="mb-4 leading-relaxed text-gray-700">
                            <p><strong>Penerima:</strong> {{ $transaction->name }} ({{ $transaction->email }})</p>
                            <p><strong>Telepon:</strong> {{ $transaction->phone }}</p>
                            <p><strong>Alamat:</strong> {{ $transaction->address }}, {{ $transaction->city }},
                                {{ $transaction->post_code }}</p>
                        </div>

                        <div class="flex justify-end mt-4">
                            {{-- Link untuk melihat detail Midtrans jika ada --}}
                            @if ($transaction->payment_url)
                                {{-- Menggunakan payment_url yang menyimpan snap token --}}
                                <a href="https://app.sandbox.midtrans.com/snap/v2/vtweb/{{ $transaction->payment_url }}"
                                    target="_blank" class="inline-block mr-4 font-medium text-blue-600 hover:text-blue-800">
                                    Lihat Detail Pembayaran Midtrans
                                </a>
                            @endif
                            {{-- Contoh tombol untuk download invoice (jika Anda punya route downloadInvoice) --}}
                            {{-- <a href="{{ route('order.downloadInvoice', $transaction->id) }}" class="px-4 py-2 text-white transition-colors duration-200 bg-gray-700 rounded-md hover:bg-gray-800">Download Invoice</a> --}}
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-lg text-center text-gray-600">Anda belum memiliki riwayat pesanan.</p>
                <div class="mt-6 text-center">
                    <a href="{{ route('product.index') }}"
                        class="px-6 py-3 text-white transition-colors duration-300 rounded-full bg-primary hover:bg-primary-dark">Mulai
                        Belanja</a>
                </div>
            @endforelse
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.toggle-details').forEach(header => {
                header.addEventListener('click', function() {
                    const targetId = this.dataset.target;
                    const content = document.querySelector(targetId);
                    const icon = this.querySelector('i.fa-chevron-down');

                    if (content.classList.contains('hidden')) {
                        content.classList.remove('hidden');
                        content.classList.add('animate__animated', 'animate__fadeInDown',
                            'animate__faster');
                        icon.classList.add('rotate-180');
                    } else {
                        content.classList.add('hidden');
                        content.classList.remove('animate__fadeInDown', 'animate__faster');
                        icon.classList.remove('rotate-180');
                    }
                });
            });
        });
    </script>
@endpush
