@extends('layouts.master')

@section('title', 'Semua Produk - Faa Roti')
@section('description', 'Jelajahi semua produk roti, kue, dan makanan penutup dari Faa Roti.')

@section('content')
    <div class="relative py-24 bg-center bg-cover page-header sm:py-32" style="background-image: url('{{ asset('assets/img/carousel-1.jpg') }}');">
        <div class="absolute inset-0 bg-black bg-opacity-60"></div>
        <div class="container relative mx-auto text-center">
            <h1 class="text-4xl font-extrabold text-white sm:text-5xl font-playfair animate__animated animate__fadeInDown">Semua Produk</h1>
            <nav class="mt-4 text-sm text-white animate__animated animate__fadeInUp" aria-label="breadcrumb">
                <ol class="inline-flex justify-center p-0 m-0 list-none">
                    <li class="flex items-center">
                        <a href="{{ route('home') }}" class="hover:text-primary">Beranda</a>
                    </li>
                    <li class="flex items-center mx-2" aria-hidden="true">/</li>
                    <li class="flex items-center" aria-current="page">
                        <span>Produk</span>
                    </li>
                </ol>
            </nav>
        </div>
    </div>
    <div class="container px-4 py-16 mx-auto sm:px-6 lg:px-8">
        {{-- Baris untuk Filter/Sortir dan Jumlah Produk --}}
        <div class="flex flex-col items-center justify-between gap-4 mb-8 sm:flex-row">
            <p class="text-gray-600">
                Menampilkan <span class="font-semibold text-gray-900">{{ $allProducts->firstItem() }}-{{ $allProducts->lastItem() }}</span> dari <span class="font-semibold text-gray-900">{{ $allProducts->total() }}</span> produk
            </p>

            <form action="{{ route('product.index') }}" method="GET">
                <div class="flex items-center space-x-2">
                    <label for="sort" class="text-sm font-medium text-gray-700">Urutkan:</label>
                    <select name="sort" id="sort" class="border-gray-300 rounded-md shadow-sm focus:border-primary focus:ring-primary" onchange="this.form.submit()">
                        <option value="terbaru" {{ request('sort') == 'terbaru' ? 'selected' : '' }}>Terbaru</option>
                        <option value="harga_asc" {{ request('sort') == 'harga_asc' ? 'selected' : '' }}>Harga (Rendah ke Tinggi)</option>
                        <option value="harga_desc" {{ request('sort') == 'harga_desc' ? 'selected' : '' }}>Harga (Tinggi ke Rendah)</option>
                        <option value="nama_asc" {{ request('sort') == 'nama_asc' ? 'selected' : '' }}>Nama (A-Z)</option>
                    </select>
                </div>
            </form>
        </div>

        {{-- Grid Produk --}}
        <div class="grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
            @forelse ($allProducts as $product)
                {{-- Menggunakan struktur kartu produk yang konsisten --}}
                <div class="flex flex-col overflow-hidden transition-transform duration-300 bg-white border border-gray-100 rounded-lg shadow-sm group product-item hover:shadow-xl hover:-translate-y-1">
                    <div class="relative">
                        <a href="{{ route('product.show', $product->id) }}">
                            <img class="object-cover w-full h-56" src="{{ asset('storage/' . $product->foto_produk) }}" alt="{{ $product->nama_produk }}" />
                        </a>
                    </div>
                    <div class="flex flex-col flex-grow p-4 text-center">
                        <h3 class="mb-2 text-lg font-semibold text-gray-900">
                            <a href="{{ route('product.show', $product->id) }}" class="hover:text-primary">{{ $product->nama_produk }}</a>
                        </h3>
                        <p class="flex-grow text-sm text-gray-500">{{ Str::limit($product->deskripsi, 50) }}</p>
                        <div class="mt-4 text-xl font-bold text-primary">
                            IDR {{ number_format($product->harga, 0, ',', '.') }}
                        </div>
                    </div>
                </div>
            @empty
                <div class="py-24 text-center text-gray-600 col-span-full">
                    <i class="mb-4 text-5xl text-gray-300 fas fa-search"></i>
                    <h3 class="text-xl font-semibold">Produk Tidak Ditemukan</h3>
                    <p class="max-w-md mx-auto mt-2">Maaf, tidak ada produk yang cocok dengan kriteria Anda. Silakan coba lagi nanti.</p>
                </div>
            @endforelse
        </div>

        {{-- Tautan Paginasi --}}
        <div class="mt-12">
            {{ $allProducts->appends(request()->query())->links() }}
        </div>

    </div>
@endsection

@push('styles')
{{-- Menambahkan sedikit style untuk paginasi default Laravel agar serasi dengan Tailwind --}}
<style>
    .pagination {
        display: flex;
        justify-content: center;
        align-items: center;
        list-style: none;
        padding: 0;
    }
    .pagination li a, .pagination li span {
        padding: 0.5rem 1rem;
        margin: 0 0.25rem;
        border-radius: 0.375rem;
        color: #4a5568;
        background-color: #fff;
        border: 1px solid #e2e8f0;
        transition: all 0.2s;
    }
    .pagination li.active span {
        background-color: #FEA116;
        color: white;
        border-color: #FEA116;
    }
    .pagination li a:hover {
        background-color: #f7fafc;
    }
    .pagination li.disabled span {
        color: #a0aec0;
        background-color: #f7fafc;
    }
</style>
@endpush
