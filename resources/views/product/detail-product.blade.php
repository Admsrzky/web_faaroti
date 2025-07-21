@extends('layouts.master') {{-- Menggunakan layout utama yang sama --}}

@section('title', 'Detail Produk: ' . $product->nama_produk) {{-- Mengisi title secara dinamis --}}
@section('description', $product->deskripsi) {{-- Mengisi description secara dinamis --}}

{{-- Anda bisa menambahkan CSS khusus untuk halaman ini jika diperlukan --}}
{{-- @push('styles')
    <link href="{{ asset('css/product-detail-custom.css') }}" rel="stylesheet">
@endpush --}}

@section('content')
    <div class="container px-6 mx-auto py-36"> {{-- Tambahkan padding-top agar tidak tertutup header --}}
        <div class="grid items-start grid-cols-1 gap-12 lg:grid-cols-2">
            <div class="animate__animated animate__fadeInLeft" data-wow-delay="0.1s">
                {{-- Menggunakan $product yang dikirim dari controller --}}
                <img class="object-cover w-full rounded-lg shadow-lg" style="height: 30rem;"
                    src="{{ asset('storage/' . $product->foto_produk) }}" alt="{{ $product->nama_produk }}" />
            </div>

            <div class="animate__animated animate__fadeInRight" data-wow-delay="0.3s">
                <h1 class="mb-4 text-4xl font-extrabold text-gray-900">
                    {{ $product->nama_produk }}
                </h1>
                <p class="mb-4 text-3xl font-bold text-primary">IDR {{ number_format($product->harga, 0, ',', '.') }}</p>
                <div class="flex items-center mb-4 text-gray-600">
                    {{-- Ini masih hardcoded, bisa Anda dinamiskkan juga jika ada rating di model product --}}
                    <i class="mr-1 fas fa-star text-primary"></i>
                    <i class="mr-1 fas fa-star text-primary"></i>
                    <i class="mr-1 fas fa-star text-primary"></i>
                    <i class="mr-1 fas fa-star text-primary"></i>
                    <i class="mr-2 fas fa-star-half-alt text-primary"></i>
                    <span>(4.5/5 Bintang)</span>
                </div>
                <p class="mb-6 leading-relaxed text-gray-700">
                    {{ $product->deskripsi }}
                </p>

                {{-- FORM UNTUK MENAMBAHKAN KE KERANJANG --}}
                <form action="{{ route('cart.add') }}" method="POST">
                    @csrf {{-- Penting untuk keamanan Laravel --}}
                    <input type="hidden" name="product_id" value="{{ $product->id }}"> {{-- Kirim ID produk --}}

                    <div class="flex items-center mb-6">
                        <label for="quantity" class="mr-4 text-lg font-semibold text-gray-800">Kuantitas:</label>
                        <input type="number" id="quantity" name="quantity" value="1" min="1"
                            max="{{ $product->stok }}"
                            class="w-24 px-4 py-2 text-lg text-center border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent" />
                    </div>

                    <button type="submit"
                        class="flex items-center justify-center px-8 py-3 text-xl font-semibold text-white transition-colors duration-300 rounded-full bg-primary hover:bg-primary-dark">
                        <i class="mr-3 fa fa-shopping-cart"></i> Tambah ke Keranjang
                    </button>
                </form>
                {{-- AKHIR FORM --}}

                <div class="mt-8 text-gray-600">
                    <p class="mb-2">
                        <strong>Ketersediaan:</strong>
                        <span class="{{ $product->stok > 0 ? 'text-green-600' : 'text-red-600' }}">
                            {{ $product->stok > 0 ? 'Stok Tersedia (' . $product->stok . ')' : 'Stok Habis' }}
                        </span>
                    </p>
                    <p class="mb-2"><strong>Kategori:</strong> {{ $product->category->nama_kategori ?? 'N/A' }}</p>
                    {{-- Asumsi Anda punya SKU di model Product, jika tidak, hapus baris ini --}}
                    {{-- <p><strong>SKU:</strong> {{ $product->sku ?? 'N/A' }}</p> --}}
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

{{-- Jika ada script khusus untuk halaman ini, tempatkan di sini --}}
@push('scripts')
    <script>
        // Script untuk menghilangkan pesan sukses/error setelah beberapa detik
        document.addEventListener('DOMContentLoaded', function() {
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
        });
    </script>
@endpush
