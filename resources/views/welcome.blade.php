@extends('layouts.master') {{-- Menggunakan layout utama --}}

@section('title', 'Beranda - Faa Roti') {{-- Menetapkan judul khusus untuk halaman ini --}}
@section('description', 'Beranda Faa Roti, temukan roti, kue, dan makanan penutup terbaik.')
{{-- Anda bisa menambahkan atau mengganti meta lainnya di sini --}}


@section('content')
    {{-- Carousel Section --}}
    <div class="owl-carousel header-carousel relative pt-[72px] lg:pt-[84px]">
        <div class="relative owl-carousel-item">
            <img class="w-full h-[600px] object-cover" src="{{ asset('assets/img/carousel-1.jpg') }}"
                alt="Carousel Toko Roti 1" />
            <div class="absolute inset-0 flex items-center justify-start bg-black bg-opacity-50">
                <div class="container px-4 mx-auto">
                    <div class="flex justify-start">
                        <div class="lg:w-8/12">
                            <p class="mb-2 font-bold uppercase text-primary">
                                Toko Roti Terbaik
                            </p>
                            <h1
                                class="mb-4 text-6xl font-extrabold text-white lg:text-7xl animate__animated animate__slideInDown">
                                Kami Membuat Dengan Penuh Gairah
                            </h1>
                            <p class="pb-3 mb-6 text-xl text-white">
                                Kelembutan dan kelimpahan topping nya tak tertandingi
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- About Section --}}
    <div class="container py-12 mx-auto">
        <div class="grid items-center grid-cols-1 gap-8 lg:grid-cols-2">
            <div class="animate__animated animate__fadeInUp" data-wow-delay="0.1s">
                <div class="relative grid h-full grid-cols-2 gap-4">
                    <div class="col-span-1">
                        <img class="w-full h-auto rounded-lg shadow-md" src="{{ asset('assets/img/about-1.jpg') }}"
                            alt="Tentang Toko Roti 1" />
                    </div>
                    <div class="self-end col-span-1">
                        <img class="w-full h-auto rounded-lg shadow-md" src="{{ asset('assets/img/about-2.jpg') }}"
                            alt="Tentang Toko Roti 2" />
                    </div>
                </div>
            </div>
            <div class="animate__animated animate__fadeInUp" data-wow-delay="0.5s">
                <div class="h-full">
                    <p class="mb-2 uppercase text-primary">Tentang Kami</p>
                    <h1 class="mb-4 text-4xl font-extrabold text-gray-900">
                        Faa Roti: Ciptakan Kebahagiaan dalam Setiap Gigitan, Dibuat dengan Penuh Cinta
                    </h1>
                    <p class="mb-4 text-gray-700">
                        Di Faa Roti, kami percaya bahwa setiap roti dan kue adalah mahakarya yang lahir dari gairah dan
                        dedikasi. Kami memadukan resep tradisional dengan inovasi modern, menggunakan bahan-bahan pilihan
                        terbaik untuk menciptakan cita rasa yang tak terlupakan. Setiap produk kami adalah hasil dari
                        sentuhan tangan ahli, memastikan kualitas dan kesegaran yang tiada duanya.
                    </p>
                    <p class="mb-6 text-gray-700">
                        Kami berkomitmen untuk menghadirkan pengalaman kuliner yang istimewa bagi Anda dan keluarga. Dari
                        aroma yang menggoda saat pertama kali dibuka, hingga kelembutan tekstur dan ledakan rasa di setiap
                        gigitan, Faa Roti adalah simbol kehangatan dan kebersamaan. Kami hadir untuk melengkapi momen
                        spesial Anda, menjadikan setiap hari lebih manis dan berkesan.
                    </p>
                    <div class="grid grid-cols-1 gap-4 mb-8 sm:grid-cols-2">
                        <div class="flex items-center">
                            <i class="mr-2 fa fa-check text-primary"></i>Produk Berkualitas Premium
                        </div>
                        <div class="flex items-center">
                            <i class="mr-2 fa fa-check text-primary"></i>Resep Autentik & Inovatif
                        </div>
                        <div class="flex items-center">
                            <i class="mr-2 fa fa-check text-primary"></i>Pesan Online Mudah & Cepat
                        </div>
                        <div class="flex items-center">
                            <i class="mr-2 fa fa-check text-primary"></i>Pengiriman Langsung ke Rumah
                        </div>
                    </div>
                    <a class="px-8 py-3 text-white transition-colors duration-300 rounded-full bg-primary hover:bg-primary-dark"
                        href="{{ route('product.index') }}">Jelajahi Produk Kami</a>
                </div>
            </div>
        </div>
    </div>

    {{-- Produk Kategori --}}
    <div class="py-12 pt-0 my-12 bg-light">
        <div class="container mx-auto">
            <div class="pt-10 mx-auto mb-12 text-center animate__animated animate__fadeInUp" data-wow-delay="0.1s"
                style="max-width: 500px">
                <h1 class="mb-4 text-4xl font-extrabold text-gray-900">
                    Jelajahi Kategori Produk Toko Roti Kami
                </h1>
            </div>

            <div class="flex flex-wrap justify-center gap-2 mb-8 animate__animated animate__fadeInUp" data-wow-delay="0.3s">
                @foreach ($categories as $category)
                    <button
                        class="tab-button bg-white text-gray-700 font-medium px-6 py-2 rounded-full shadow-md hover:bg-gray-100 focus:outline-none transition-colors duration-200 {{ $loop->first ? 'active-tab' : '' }}"
                        data-category="{{ Str::slug($category->nama_kategori) }}">
                        {{ $category->nama_kategori }}
                    </button>
                @endforeach
            </div>

            @foreach ($categories as $category)
                <div id="{{ Str::slug($category->nama_kategori) }}-content"
                    class="tab-content grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 animate__animated animate__fadeInUp {{ $loop->first ? '' : 'hidden' }}"
                    data-wow-delay="0.1s">
                    @forelse ($category->products as $product)
                        <div class="flex flex-col h-full overflow-hidden bg-white rounded-lg shadow-md product-item">
                            <div class="p-6 text-center">
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
                        <p class="text-center text-gray-600 col-span-full">No products found in this category.</p>
                    @endforelse
                </div>
            @endforeach
        </div>
    </div>
    {{-- Produk Kategori End --}}

    {{-- Product Populer - Dynamic Data --}}
    <div class="container py-12 mx-auto">
        <div class="mx-auto mb-12 text-center animate__animated animate__fadeInUp" data-wow-delay="0.1s"
            style="max-width: 500px">
            <h1 class="mb-4 text-4xl font-extrabold text-gray-900">
                Produk Terlaris Kami
            </h1>
            <p class="text-gray-700">
                Lihat pilihan produk paling populer dan favorit pelanggan kami.
            </p>
        </div>
        <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
            @forelse ($popularProducts as $product)
                <div class="flex flex-col h-full overflow-hidden bg-white rounded-lg shadow-md product-item animate__animated animate__fadeInUp"
                    data-wow-delay="0.1s"> {{-- data-wow-delay bisa disesuaikan atau dihilangkan jika tidak perlu animasi individual --}}
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
                <p class="text-center text-gray-600 col-span-full">Tidak ada produk populer yang ditemukan.</p>
            @endforelse
        </div>
    </div>
    {{-- Product Populer End --}}
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Fungsionalitas Tab Kategori
            const tabButtons = document.querySelectorAll(".tab-button");
            const tabContents = document.querySelectorAll(".tab-content");

            function showCategory(category) {
                tabContents.forEach((content) => {
                    content.classList.add("hidden");
                    content.classList.remove("grid"); // Penting: hapus grid agar hidden bekerja
                });

                tabButtons.forEach((button) => {
                    button.classList.remove("active-tab", "bg-primary", "text-white");
                    button.classList.add("bg-white", "text-gray-700");
                });

                const targetContent = document.getElementById(category + "-content");
                if (targetContent) {
                    targetContent.classList.remove("hidden");
                    targetContent.classList.add("grid"); // Penting: tambahkan grid saat ditampilkan
                    targetContent.classList.add("animate__animated", "animate__fadeInUp"); // Re-trigger animation
                }

                const activeButton = document.querySelector(
                    `.tab-button[data-category="${category}"]`
                );
                if (activeButton) {
                    activeButton.classList.add("active-tab", "bg-primary", "text-white");
                    activeButton.classList.remove("bg-white", "text-gray-700");
                }
            }

            tabButtons.forEach((button) => {
                button.addEventListener("click", () => {
                    const category = button.dataset.category;
                    showCategory(category);
                });
            });

            const initialCategoryButton = document.querySelector(".tab-button.active-tab");
            if (initialCategoryButton) {
                showCategory(initialCategoryButton.dataset.category);
            } else if (tabButtons.length > 0) {
                tabButtons[0].classList.add("active-tab", "bg-primary", "text-white");
                tabButtons[0].classList.remove("bg-white", "text-gray-700");
                showCategory(tabButtons[0].dataset.category);
            }
        });
    </script>
@endpush
