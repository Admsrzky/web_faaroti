@extends('layouts.master') {{-- Menggunakan layout utama yang sama --}}

@section('title', 'Keranjang Belanja - Faa Roti') {{-- Menetapkan judul khusus untuk halaman ini --}}
@section('description',
    'Lihat dan kelola item di keranjang belanja Anda. Lanjutkan ke pembayaran untuk menyelesaikan
    pesanan di Faa Roti.')
    {{-- Anda bisa menambahkan atau mengganti meta lainnya di sini --}}

@section('content')
    <div class="container py-32 mx-auto">
        <div class="grid grid-cols-1 gap-8 lg:grid-cols-3">
            <div class="lg:col-span-2">
                <div class="p-6 bg-white rounded-lg shadow-md animate__animated animate__fadeInUp" data-wow-delay="0.1s">
                    <h2 class="mb-6 text-2xl font-bold text-gray-900">
                        Isi Keranjang Anda
                    </h2>

                    @if ($cartItems->isEmpty())
                        <p class="py-8 text-center text-gray-600">Keranjang belanja Anda kosong.</p>
                    @else
                        @foreach ($cartItems as $item)
                            <div class="flex items-center py-4 border-b border-gray-200 last:border-b-0">
                                <div class="flex-shrink-0 w-24 h-24 overflow-hidden border border-gray-200 rounded-md">
                                    <img src="{{ asset('storage/' . $item->product->foto_produk) }}"
                                        alt="{{ $item->product->nama_produk }}"
                                        class="object-cover object-center w-full h-full" />
                                </div>
                                <div class="flex flex-col flex-1 ml-4">
                                    <div>
                                        <div class="flex justify-between text-base font-medium text-gray-900">
                                            <h3><a
                                                    href="{{ route('product.index', $item->product->id) }}">{{ $item->product->nama_produk }}</a>
                                            </h3>
                                            <p class="ml-4">Rp
                                                {{ number_format($item->product->harga * $item->quantity, 0, ',', '.') }}
                                            </p>
                                        </div>
                                        <p class="mt-1 text-sm text-gray-500">{{ $item->product->deskripsi }}</p>
                                    </div>
                                    <div class="flex items-end justify-between flex-1 mt-2 text-sm">
                                        {{-- Form untuk memperbarui kuantitas --}}
                                        <form action="{{ route('cart.updateQuantity', $item->id) }}" method="POST"
                                            class="flex items-center">
                                            @csrf
                                            @method('PUT') {{-- Gunakan metode PUT untuk update --}}
                                            <label for="quantity-{{ $item->id }}" class="mr-2">Kuantitas:</label>
                                            <input type="number" id="quantity-{{ $item->id }}" name="quantity"
                                                value="{{ $item->quantity }}" min="1"
                                                max="{{ $item->product->stok }}"
                                                class="w-16 px-2 py-1 text-center border border-gray-300 rounded-md quantity-input" />
                                            <button type="submit"
                                                class="px-3 py-1 ml-2 text-xs text-white bg-blue-500 rounded-md hover:bg-blue-600">Update</button>
                                        </form>

                                        {{-- Form untuk menghapus item --}}
                                        <form action="{{ route('cart.remove', $item->id) }}" method="POST" class="flex">
                                            @csrf
                                            @method('DELETE') {{-- Gunakan metode DELETE untuk hapus --}}
                                            <button type="submit" class="font-medium text-red-600 hover:text-red-800">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>

            <div class="lg:col-span-1">
                <div class="p-6 bg-white rounded-lg shadow-md animate__animated animate__fadeInUp" data-wow-delay="0.3s">
                    <h2 class="mb-6 text-2xl font-bold text-gray-900">
                        Ringkasan Belanja
                    </h2>
                    <div class="py-4 border-t border-gray-200">
                        <div class="flex justify-between mb-2 text-base font-medium text-gray-900">
                            <p>Subtotal</p>
                            <p>Rp {{ number_format($subtotal, 0, ',', '.') }}</p>
                        </div>
                        <div class="flex justify-between mb-2 text-base font-medium text-gray-900">
                            <p>Ongkos Kirim</p>
                            <p>Rp {{ number_format($shippingCost, 0, ',', '.') }}</p>
                        </div>
                        <div
                            class="flex justify-between pt-4 mt-4 text-xl font-bold text-gray-900 border-t border-gray-300">
                            <p>Total</p>
                            <p>Rp {{ number_format($total, 0, ',', '.') }}</p>
                        </div>
                    </div>

                    <div class="mt-6 text-center">
                        <a href="{{ route('order.checkout') }}"
                            class="block w-full px-6 py-3 text-lg font-semibold text-white transition-colors duration-300 rounded-full bg-primary hover:bg-primary-dark">Checkout</a>
                    </div>
                    <div class="mt-4 text-center">
                        <p class="text-sm text-gray-500">
                            atau
                            <a href="{{ route('product.index') }}"
                                class="font-medium text-primary hover:text-primary-dark">Lanjutkan Belanja</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Untuk menampilkan pesan sukses/error --}}
    @if (session('success'))
        <div
            class="fixed p-4 text-white bg-green-500 rounded-lg shadow-lg bottom-4 right-4 animate__animated animate__fadeInUp">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div
            class="fixed p-4 text-white bg-red-500 rounded-lg shadow-lg bottom-4 right-4 animate__animated animate__fadeInUp">
            {{ session('error') }}
        </div>
    @endif
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Script untuk menghilangkan pesan sukses/error setelah beberapa detik
            const successMessage = document.querySelector('.bg-green-500');
            const errorMessage = document.querySelector('.bg-red-500');

            if (successMessage) {
                setTimeout(() => {
                    successMessage.classList.remove('animate__fadeInUp');
                    successMessage.classList.add('animate__fadeOutDown');
                    setTimeout(() => successMessage.remove(), 500); // Hapus setelah animasi selesai
                }, 3000); // Hilangkan setelah 3 detik
            }

            if (errorMessage) {
                setTimeout(() => {
                    errorMessage.classList.remove('animate__fadeInUp');
                    errorMessage.classList.add('animate__fadeOutDown');
                    setTimeout(() => errorMessage.remove(), 500); // Hapus setelah animasi selesai
                }, 3000); // Hilangkan setelah 3 detik
            }

            // JavaScript untuk mengirim form update kuantitas saat input berubah
            // Ini dihapus karena kita sudah punya tombol "Update" yang akan submit form
            // document.querySelectorAll('.quantity-input').forEach(input => {
            //     input.addEventListener('change', function() {
            //         this.closest('form').submit(); // Submit form terdekat
            //     });
            // });
        });
    </script>
@endpush
