@extends('layouts.master')

@section('title', 'Riwayat Pesanan - Faa Roti')
@section('description', 'Lihat riwayat pesanan Anda di Faa Roti.')

@section('content')
    <div class="container px-4 py-16 mx-auto sm:px-6 lg:px-8">
        <div class="p-6 bg-white rounded-lg shadow-md animate__animated animate__fadeInUp" data-wow-delay="0.1s">
            <h2 class="pb-6 mb-6 text-2xl font-bold text-gray-900 border-b">Daftar Pesanan Anda</h2>

            @forelse ($transactions as $transaction)
                {{-- Accordion dengan Alpine.js --}}
                <div x-data="{ open: false }" class="p-4 mb-6 transition-shadow duration-200 border border-gray-200 rounded-lg last:mb-0 bg-gray-50 hover:shadow-lg">
                    {{-- Header Ringkasan Pesanan --}}
                    <div @click="open = !open" class="flex flex-col items-start justify-between cursor-pointer md:flex-row md:items-center">
                        <h3 class="mb-2 text-xl font-semibold text-gray-800 md:mb-0">Order ID: <span class="text-primary">{{ $transaction->trx_id ?? $transaction->id }}</span></h3>
                        <div class="flex flex-col items-start gap-2 md:flex-row md:items-center">
                            <span class="text-sm text-gray-600">Tanggal: {{ $transaction->created_at->format('d M Y') }}</span>
                            <span class="px-3 py-1 text-sm font-semibold rounded-full
                                @switch($transaction->status)
                                    @case('pending') bg-yellow-100 text-yellow-800 @break
                                    @case('dikirim') bg-blue-100 text-blue-800 @break
                                    @case('selesai') bg-green-100 text-green-800 @break
                                    @case('dibatalkan') bg-red-100 text-red-800 @break
                                    @default bg-gray-100 text-gray-800
                                @endswitch">
                                {{ ucfirst($transaction->status) }}
                            </span>
                            <i class="ml-2 text-gray-500 transition-transform duration-300 fas fa-chevron-down" :class="{ 'rotate-180': open }"></i>
                        </div>
                    </div>

                    {{-- Detail Pesanan (Tersembunyi) --}}
                    <div x-show="open" x-transition class="pt-4 mt-4 border-t border-gray-200">
                        {{-- ... Konten Detail ... --}}
                        <div class="grid grid-cols-1 gap-6 mb-4 md:grid-cols-2">
                            <div>
                                <p class="font-medium text-gray-700">Total Pembayaran:</p>
                                <p class="text-2xl font-bold text-primary">Rp {{ number_format($transaction->grand_total, 0, ',', '.') }}</p>
                            </div>
                             <div>
                                <p class="font-medium text-gray-700">Status Pembayaran:</p>
                                <span class="px-3 py-1 inline-block text-sm font-semibold rounded-full {{ $transaction->payment_status == 'settlement' || $transaction->payment_status == 'success' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                    {{ ucfirst($transaction->payment_status) }}
                                </span>
                            </div>
                        </div>

                        <h4 class="mb-3 text-lg font-semibold text-gray-800">Detail Produk:</h4>
                        <div class="mb-4 space-y-3">
                            @foreach ($transaction->items as $item)
                                <div class="flex items-center p-3 bg-white border border-gray-100 rounded-md shadow-sm">
                                    <img src="{{ asset('storage/' . $item->product->foto_produk) }}" alt="{{ $item->product->nama_produk }}" class="object-cover w-16 h-16 mr-4 rounded-md">
                                    <div class="flex-1">
                                        <p class="font-medium text-gray-900">{{ $item->quantity }}x {{ $item->product->nama_produk }}</p>
                                        <p class="text-sm text-gray-600">@ Rp {{ number_format($item->product->harga, 0, ',', '.') }}</p>
                                    </div>
                                    <p class="font-semibold text-gray-800">Rp {{ number_format($item->product->harga * $item->quantity, 0, ',', '.') }}</p>
                                </div>
                            @endforeach
                        </div>

                        <h4 class="mb-3 text-lg font-semibold text-gray-800">Informasi Pengiriman:</h4>
                        <div class="mb-4 leading-relaxed text-gray-700">
                             <p><strong>Penerima:</strong> {{ $transaction->name }}</p>
                             <p><strong>Alamat:</strong> {{ $transaction->address }}, {{ $transaction->city }}, {{ $transaction->post_code }}</p>
                        </div>

                        {{-- TOMBOL BARU: PESANAN DITERIMA --}}
                        <div class="flex justify-end pt-4 mt-4 border-t">
                            @if ($transaction->status == 'dikirim')
                                <form action="{{ route('order.complete', $transaction->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="px-6 py-2 font-semibold text-white transition-colors duration-300 rounded-full bg-primary hover:bg-primary-dark">
                                        Pesanan Diterima
                                    </button>
                                </form>
                            @else
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="py-16 text-center">
                    <i class="mb-4 text-6xl text-gray-300 fas fa-receipt"></i>
                    <h3 class="text-xl font-semibold text-gray-800">Anda Belum Memiliki Riwayat Pesanan</h3>
                    <p class="mt-2 text-gray-500">Semua pesanan yang Anda buat akan muncul di sini.</p>
                    <a href="{{ route('product.index') }}" class="inline-block px-8 py-3 mt-6 font-semibold text-white transition-transform duration-300 rounded-full bg-primary hover:bg-primary-dark hover:scale-105">
                        Mulai Belanja
                    </a>
                </div>
            @endforelse
        </div>
    </div>
@endsection
