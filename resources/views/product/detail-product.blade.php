@extends('layouts.master') {{-- Menggunakan layout utama yang sama --}}

@section('title', 'Detail Produk: ' . $product->nama_produk) {{-- Mengisi title secara dinamis --}}
@section('description', $product->deskripsi) {{-- Mengisi description secara dinamis --}}

@section('content')
    {{-- Padding container dibuat responsif --}}
    <div class="container px-4 py-16 mx-auto sm:px-6 lg:px-8 sm:py-24">
        <div class="grid items-start grid-cols-1 gap-12 lg:grid-cols-2">

            <div class="w-full animate__animated animate__fadeInLeft" data-wow-delay="0.1s">
                {{-- Menggunakan aspect ratio untuk gambar yang responsif --}}
                <div class="overflow-hidden rounded-lg shadow-lg aspect-square">
                    <img class="object-cover w-full h-full"
                         src="{{ asset('storage/' . $product->foto_produk) }}"
                         alt="{{ $product->nama_produk }}" />
                </div>
            </div>

            <div class="flex flex-col h-full animate__animated animate__fadeInRight" data-wow-delay="0.3s">
                {{-- Kategori sebagai "badge" --}}
                <p class="mb-2 font-semibold uppercase text-primary">{{ $product->category->nama_kategori ?? 'N/A' }}</p>

                {{-- Ukuran font judul dibuat responsif --}}
                <h1 class="mb-3 text-3xl font-extrabold tracking-tight text-gray-900 sm:text-4xl">
                    {{ $product->nama_produk }}
                </h1>

                {{-- Ukuran font harga dibuat responsif --}}
                <p class="mb-5 text-2xl font-bold sm:text-3xl text-primary">IDR {{ number_format($product->harga, 0, ',', '.') }}</p>

                <div class="mb-6 prose text-gray-600 max-w-none">
                    <p>{{ $product->deskripsi }}</p>
                </div>

                {{-- Status stok --}}
                <div class="mb-6">
                     <p class="text-sm">
                         <strong>Ketersediaan:</strong>
                         <span class="{{ $product->stok > 0 ? 'text-green-600 font-semibold' : 'text-red-600 font-semibold' }}">
                             {{ $product->stok > 0 ? 'Stok Tersedia (' . $product->stok . ')' : 'Stok Habis' }}
                         </span>
                     </p>
                </div>

                {{-- FORM UNTUK MENAMBAHKAN KE KERANJANG --}}
                {{-- Hanya tampilkan form jika stok tersedia --}}
                @if($product->stok > 0)
                    <form action="{{ route('cart.add') }}" method="POST" class="mt-auto">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">

                        <div class="flex flex-col gap-4 sm:flex-row">
                             {{-- Input kuantitas dibuat lebih modern --}}
                            <div class="flex items-center">
                                <label for="quantity" class="sr-only">Kuantitas</label>
                                <input type="number" id="quantity" name="quantity" value="1" min="1"
                                       max="{{ $product->stok }}"
                                       class="w-24 px-3 py-3 text-base text-center border-gray-300 rounded-md shadow-sm focus:ring-primary focus:border-primary" />
                            </div>

                            {{-- Tombol dibuat full-width di mobile --}}
                            <button type="submit"
                                    class="flex items-center justify-center w-full px-8 py-3 text-base font-semibold text-white transition-transform duration-300 rounded-md shadow-sm bg-primary hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary hover:scale-105 sm:w-auto">
                                <i class="mr-2 fa fa-shopping-cart"></i> Tambah ke Keranjang
                            </button>
                        </div>
                    </form>
                @else
                    {{-- Pesan jika stok habis --}}
                    <div class="p-4 mt-auto text-center bg-gray-100 rounded-md">
                        <p class="font-semibold text-gray-700">Mohon maaf, produk ini sedang tidak tersedia.</p>
                    </div>
                @endif
                {{-- AKHIR FORM --}}
            </div>
        </div>
    </div>

    {{-- Pesan notifikasi (Toast) dibuat lebih baik untuk mobile --}}
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
            // Script untuk menghilangkan pesan notifikasi setelah beberapa detik
            const toast = document.querySelector('.toast-notification');

            if (toast) {
                setTimeout(() => {
                    toast.classList.remove('animate__fadeInUp');
                    toast.classList.add('animate__fadeOutDown');
                    setTimeout(() => toast.remove(), 500); // Hapus elemen setelah animasi selesai
                }, 4000); // Hilangkan setelah 4 detik
            }
        });
    </script>
@endpush
