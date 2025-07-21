@extends('layouts.master') {{-- Menggunakan layout utama --}}

@section('title', 'Semua Produk - Faa Roti') {{-- Menetapkan judul khusus untuk halaman ini --}}
@section('description', 'Jelajahi semua produk roti, kue, dan makanan penutup dari Faa Roti.')

@section('content')
    <div class="py-10 container-fluid page-header wow fadeIn" data-wow-delay="0.1s"></div>
    <!-- Page Header End -->

    <div class="container py-12 mx-auto">
        <div class="mx-auto mb-12 text-center animate__animated animate__fadeInUp" data-wow-delay="0.1s"
            style="max-width: 500px">
            <h1 class="pt-6 mb-4 text-3xl font-extrabold text-gray-900 lg:text-4xl">
                Daftar Lengkap Produk Kami
            </h1>
            <p class="text-gray-700">
                Temukan semua varian roti, kue, dan makanan penutup lezat yang kami tawarkan.
            </p>
        </div>

        <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
            @forelse ($allProducts as $product)
                <div class="flex flex-col h-full overflow-hidden bg-white rounded-lg shadow-md product-item animate__animated animate__fadeInUp"
                    data-wow-delay="0.1s">
                    <div class="p-6 text-center">
                        <div class="inline-block px-4 py-1 mb-3 border rounded-full border-primary text-primary">
                            IDR {{ number_format($product->harga, 0, ',', '.') }}
                        </div>
                        <h3 class="mb-3 text-2xl font-semibold text-gray-900">
                            {{ $product->nama_produk }}
                        </h3>
                        <span class="text-gray-700">{{ $product->deskripsi }}</span>
                    </div>
                    <div class="relative mt-auto">
                        <img class="object-cover w-full h-64" src="{{ asset('storage/' . $product->foto_produk) }}"
                            alt="{{ $product->nama_produk }}" />
                        <div
                            class="absolute inset-0 flex items-center justify-center transition-opacity duration-300 bg-black bg-opacity-50 opacity-0 product-overlay hover:opacity-100">
                            <a class="flex items-center justify-center w-12 h-12 text-xl transition-colors duration-300 bg-white rounded-full text-primary hover:bg-primary hover:text-white"
                                href="{{ route('product.show', $product->id) }}"><i class="fa fa-eye"></i></a>
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-center text-gray-600 col-span-full">Tidak ada produk yang ditemukan.</p>
            @endforelse
        </div>
    </div>
@endsection
