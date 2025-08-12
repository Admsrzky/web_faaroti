@extends('layouts.master')

@section('title', 'Keranjang Belanja - Faa Roti')
@section('description', 'Lihat dan kelola item di keranjang belanja Anda. Lanjutkan ke pembayaran untuk menyelesaikan pesanan di Faa Roti.')

@section('content')
    <div class="container px-4 py-16 mx-auto sm:px-6 lg:px-8">
        @if ($cartItems->isEmpty())
            {{-- Tampilan Keranjang Kosong yang Lebih Baik --}}
            <div class="py-24 text-center bg-white rounded-lg shadow-md animate__animated animate__fadeInUp">
                <div class="flex justify-center mb-4">
                    <i class="text-6xl text-gray-300 fas fa-shopping-cart"></i>
                </div>
                <h2 class="text-2xl font-bold text-gray-800">Keranjang Anda Kosong</h2>
                <p class="mt-2 text-gray-500">Sepertinya Anda belum menambahkan produk apa pun.</p>
                <a href="{{ route('product.index') }}" class="inline-block px-8 py-3 mt-6 font-semibold text-white transition-transform duration-300 rounded-full bg-primary hover:bg-primary-dark hover:scale-105">
                    Mulai Belanja
                </a>
            </div>
        @else
            <div class="grid grid-cols-1 gap-12 lg:grid-cols-3">
                {{-- Daftar Item Keranjang --}}
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-lg shadow-md animate__animated animate__fadeInUp" data-wow-delay="0.1s">
                        <h2 class="p-6 text-2xl font-bold text-gray-900 border-b">
                            Isi Keranjang Anda ({{ $cartItems->count() }} item)
                        </h2>
                        <div class="divide-y divide-gray-200">
                            @foreach ($cartItems as $item)
                                {{-- Kartu Item yang Responsif --}}
                                <div class="flex flex-col p-6 sm:flex-row">
                                    <div class="flex-shrink-0 w-32 h-32 mx-auto sm:mx-0 sm:w-24 sm:h-24">
                                        <img src="{{ asset('storage/' . $item->product->foto_produk) }}" alt="{{ $item->product->nama_produk }}" class="object-cover w-full h-full rounded-md" />
                                    </div>
                                    <div class="flex flex-col flex-1 mt-4 text-center sm:ml-6 sm:mt-0 sm:text-left">
                                        <div class="flex flex-col justify-between sm:flex-row">
                                            <h3 class="text-lg font-semibold text-gray-800">
                                                <a href="{{ route('product.show', $item->product->id) }}" class="hover:text-primary">{{ $item->product->nama_produk }}</a>
                                            </h3>
                                            <p class="mt-1 font-semibold text-gray-900 sm:ml-4 sm:mt-0">Rp {{ number_format($item->product->harga * $item->quantity, 0, ',', '.') }}</p>
                                        </div>
                                        <p class="mt-1 text-sm text-gray-500">Harga Satuan: Rp {{ number_format($item->product->harga, 0, ',', '.') }}</p>

                                        {{-- Baris Aksi (Update & Hapus) --}}
                                        <div class="flex items-center justify-center mt-4 space-x-4 sm:justify-start">
                                            <form action="{{ route('cart.updateQuantity', $item->id) }}" method="POST" class="flex items-center">
                                                @csrf
                                                @method('PUT')
                                                <label for="quantity-{{ $item->id }}" class="sr-only">Kuantitas</label>
                                                <input type="number" id="quantity-{{ $item->id }}" name="quantity" value="{{ $item->quantity }}" min="1" max="{{ $item->product->stok }}" class="w-20 px-2 py-1 text-center border-gray-300 rounded-md shadow-sm quantity-input focus:ring-primary focus:border-primary" onchange="this.form.submit()" />
                                            </form>

                                            <form action="{{ route('cart.remove', $item->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-gray-400 transition-colors hover:text-red-600" title="Hapus item">
                                                    <i class="text-xl fas fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- Ringkasan Belanja --}}
                <div class="lg:col-span-1">
                    <div class="sticky top-28">
                        <div class="p-6 bg-white rounded-lg shadow-md animate__animated animate__fadeInUp" data-wow-delay="0.3s">
                            <h2 class="pb-4 mb-4 text-2xl font-bold text-gray-900 border-b">
                                Ringkasan Belanja
                            </h2>
                            <div class="space-y-2 text-gray-700">
                                <div class="flex justify-between">
                                    <p>Subtotal</p>
                                    <p class="font-semibold">Rp {{ number_format($subtotal, 0, ',', '.') }}</p>
                                </div>
                                <div class="flex justify-between">
                                    <p>Ongkos Kirim</p>
                                    <p class="font-semibold">Rp {{ number_format($shippingCost, 0, ',', '.') }}</p>
                                </div>
                            </div>
                            <div class="flex justify-between pt-4 mt-4 text-xl font-bold text-gray-900 border-t border-gray-300">
                                <p>Total</p>
                                <p>Rp {{ number_format($total, 0, ',', '.') }}</p>
                            </div>

                            <div class="mt-8 space-y-4">
                                <a href="{{ route('order.checkout') }}" class="block w-full px-6 py-3 text-lg font-semibold text-center text-white transition-transform duration-300 rounded-full bg-primary hover:bg-primary-dark hover:scale-105">Lanjut ke Checkout</a>
                                <a href="{{ route('product.index') }}" class="block w-full px-6 py-3 text-lg font-semibold text-center transition-colors duration-300 border rounded-full border-primary text-primary hover:bg-primary hover:text-white">Lanjutkan Belanja</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

    {{-- Notifikasi Toast --}}
    @if (session('success') || session('error'))
        @php
            $isSuccess = session('success');
            $message = $isSuccess ? session('success') : session('error');
            $bgColor = $isSuccess ? 'bg-green-500' : 'bg-red-500';
        @endphp
        <div class="toast-notification fixed p-4 text-white rounded-lg shadow-lg bottom-5 left-5 right-5 sm:left-auto sm:w-auto sm:max-w-sm animate__animated animate__fadeInUp {{ $bgColor }}">
            {{ $message }}
        </div>
    @endif
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Script untuk menghilangkan pesan notifikasi
            const toast = document.querySelector('.toast-notification');
            if (toast) {
                setTimeout(() => {
                    toast.classList.remove('animate__fadeInUp');
                    toast.classList.add('animate__fadeOutDown');
                    setTimeout(() => toast.remove(), 500);
                }, 4000);
            }

            // Script untuk auto-submit form saat kuantitas diubah
            // Ini membuat pengguna tidak perlu klik tombol "Update" manual
            document.querySelectorAll('.quantity-input').forEach(input => {
                input.addEventListener('change', function() {
                    this.closest('form').submit();
                });
            });
        });
    </script>
@endpush
