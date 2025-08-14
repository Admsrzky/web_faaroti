@extends('layouts.master') {{-- Menggunakan layout utama --}}

@section('title', 'Beranda - Faa Roti') {{-- Menetapkan judul khusus untuk halaman ini --}}
@section('description', 'Beranda Faa Roti, temukan roti, kue, dan makanan penutup terbaik.')

@section('content')

    {{-- Carousel/Hero Section --}}
    <div class="relative owl-carousel header-carousel">
        <div class="relative owl-carousel-item">
            {{-- Tinggi gambar kini lebih responsif --}}
            <img class="object-cover w-full h-[70vh] md:h-[600px]" src="{{ asset('assets/img/carousel-1.jpg') }}" alt="Carousel Toko Roti 1" />
            {{-- Konten di tengah untuk tampilan mobile yang lebih baik --}}
            <div class="absolute inset-0 flex flex-col items-center justify-center p-4 text-center bg-black bg-opacity-50">
                <div class="max-w-4xl">
                    <p class="mb-2 font-bold uppercase text-primary animate__animated animate__fadeInDown">
                        Toko Roti Terbaik
                    </p>
                    {{-- Ukuran font lebih responsif --}}
                    <h1 class="mb-4 text-4xl font-extrabold text-white sm:text-5xl lg:text-7xl animate__animated animate__slideInDown">
                        Kami Membuat Dengan Penuh Gairahhhh
                    </h1>
                    <p class="pb-3 mb-6 text-base text-white sm:text-lg lg:text-xl animate__animated animate__fadeInUp">
                        Kelembutan dan kelimpahan topping-nya tak tertandingi.
                    </p>
                </div>
            </div>
        </div>
        {{-- Anda bisa menambahkan item carousel lain di sini dengan struktur yang sama --}}
    </div>

    {{-- About Section --}}
    <div class="container px-4 py-16 mx-auto sm:px-6 lg:px-8">
        <div class="grid items-center grid-cols-1 gap-12 lg:grid-cols-2">
            <div class="animate__animated animate__fadeInUp" data-wow-delay="0.1s">
                {{-- Dibuat lebih menarik dengan perataan dan bayangan yang lebih baik --}}
                <div class="relative grid grid-cols-2 gap-4">
                    <img class="w-full h-auto transition duration-300 rounded-lg shadow-lg hover:shadow-xl" src="{{ asset('assets/img/about-1.jpg') }}" alt="Tentang Toko Roti 1" />
                    <img class="self-end w-full h-auto mt-8 transition duration-300 rounded-lg shadow-lg hover:shadow-xl" src="{{ asset('assets/img/about-2.jpg') }}" alt="Tentang Toko Roti 2" />
                </div>
            </div>
            <div class="animate__animated animate__fadeInUp" data-wow-delay="0.3s">
                <p class="mb-2 font-semibold uppercase text-primary">Tentang Kami</p>
                {{-- Ukuran font lebih responsif --}}
                <h2 class="mb-4 text-3xl font-extrabold text-gray-900 md:text-4xl">
                    Faa Roti: Ciptakan Kebahagiaan dalam Setiap Gigitan
                </h2>
                <p class="mb-4 text-gray-600">
                    Di Faa Roti, kami percaya bahwa setiap roti dan kue adalah mahakarya yang lahir dari gairah dan dedikasi. Kami memadukan resep tradisional dengan inovasi modern untuk menciptakan cita rasa yang tak terlupakan.
                </p>
                <p class="mb-6 text-gray-600">
                    Kami berkomitmen untuk menghadirkan pengalaman kuliner istimewa bagi Anda, menjadikan setiap hari lebih manis dan berkesan.
                </p>
                <div class="grid grid-cols-1 gap-4 mb-8 sm:grid-cols-2">
                    <div class="flex items-center"><i class="mr-3 fa fa-check text-primary"></i>Produk Berkualitas Premium</div>
                    <div class="flex items-center"><i class="mr-3 fa fa-check text-primary"></i>Resep Autentik & Inovatif</div>
                    <div class="flex items-center"><i class="mr-3 fa fa-check text-primary"></i>Pesan Online Mudah & Cepat</div>
                    <div class="flex items-center"><i class="mr-3 fa fa-check text-primary"></i>Pengiriman Langsung ke Rumah</div>
                </div>
                <a class="inline-block px-8 py-3 font-semibold text-white transition-transform duration-300 rounded-full bg-primary hover:bg-primary-dark hover:scale-105" href="{{ route('product.index') }}">
                    Jelajahi Produk Kami
                </a>
            </div>
        </div>
    </div>

    {{-- Produk Kategori --}}
    <div class="py-16 bg-light">
        <div class="container px-4 mx-auto sm:px-6 lg:px-8">
            <div class="mx-auto mb-12 text-center animate__animated animate__fadeInUp" data-wow-delay="0.1s" style="max-width: 600px">
                <h2 class="mb-4 text-3xl font-extrabold text-gray-900 md:text-4xl">
                    Jelajahi Kategori Produk Kami
                </h2>
            </div>

            <div class="flex flex-wrap justify-center gap-3 mb-10 animate__animated animate__fadeInUp" data-wow-delay="0.2s">
                @foreach ($categories as $category)
                    <button class="tab-button bg-white text-gray-800 font-semibold px-6 py-2.5 rounded-full shadow-sm hover:bg-gray-100 focus:outline-none transition-all duration-200" data-category="{{ Str::slug($category->nama_kategori) }}">
                        {{ $category->nama_kategori }}
                    </button>
                @endforeach
            </div>

            @foreach ($categories as $category)
                <div id="{{ Str::slug($category->nama_kategori) }}-content" class="tab-content grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 animate__animated animate__fadeInUp {{ $loop->first ? 'grid' : 'hidden' }}" data-wow-delay="0.3s">
                    @forelse ($category->products->take(6) as $product) {{-- Mengambil 6 produk per kategori --}}
                        {{-- Struktur Kartu Produk Baru --}}
                        <div class="flex flex-col overflow-hidden transition duration-300 bg-white rounded-lg shadow-md product-item hover:shadow-xl">
                            <div class="relative">
                                <a href="{{ route('product.show', $product->id) }}">
                                    <img class="object-cover w-full h-64" src="{{ asset('storage/' . $product->foto_produk) }}" alt="{{ $product->nama_produk }}" />
                                </a>
                                <div class="absolute inset-0 flex items-center justify-center transition-opacity duration-300 bg-black bg-opacity-50 opacity-0 product-overlay hover:opacity-100">
                                    <a class="flex items-center justify-center w-12 h-12 text-xl transition-transform duration-300 bg-white rounded-full text-primary hover:bg-primary hover:text-white hover:scale-110" href="{{ route('product.show', $product->id) }}">
                                        <i class="fa fa-eye"></i>
                                    </a>
                                </div>
                            </div>
                            <div class="flex flex-col flex-grow p-5 text-center">
                                <h3 class="mb-2 text-xl font-bold text-gray-900">
                                    <a href="{{ route('product.show', $product->id) }}" class="hover:text-primary">{{ $product->nama_produk }}</a>
                                </h3>
                                <p class="flex-grow text-sm text-gray-600">{{ Str::limit($product->deskripsi, 50) }}</p>
                                <div class="mt-4 text-lg font-semibold text-primary">
                                    IDR {{ number_format($product->harga, 0, ',', '.') }}
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-center text-gray-600 col-span-full">Belum ada produk di kategori ini.</p>
                    @endforelse
                </div>
            @endforeach
        </div>
    </div>

    {{-- Produk Populer --}}
    <div class="container px-4 py-16 mx-auto sm:px-6 lg:px-8">
        <div class="mx-auto mb-12 text-center animate__animated animate__fadeInUp" data-wow-delay="0.1s" style="max-width: 600px">
            <h2 class="mb-4 text-3xl font-extrabold text-gray-900 md:text-4xl">
                Produk Terlaris Kami
            </h2>
            <p class="text-gray-600">
                Lihat pilihan produk paling populer dan menjadi favorit pelanggan setia kami.
            </p>
        </div>
        <div class="grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-3">
            @forelse ($popularProducts as $product)
                {{-- Menggunakan struktur kartu yang sama untuk konsistensi --}}
                <div class="flex flex-col overflow-hidden transition duration-300 bg-white rounded-lg shadow-md product-item animate__animated animate__fadeInUp hover:shadow-xl" data-wow-delay="0.1s">
                    <div class="relative">
                         <a href="{{ route('product.show', $product->id) }}">
                             <img class="object-cover w-full h-64" src="{{ asset('storage/' . $product->foto_produk) }}" alt="{{ $product->nama_produk }}" />
                         </a>
                        <div class="absolute inset-0 flex items-center justify-center transition-opacity duration-300 bg-black bg-opacity-50 opacity-0 product-overlay hover:opacity-100">
                            <a class="flex items-center justify-center w-12 h-12 text-xl transition-transform duration-300 bg-white rounded-full text-primary hover:bg-primary hover:text-white hover:scale-110" href="{{ route('product.show', $product->id) }}">
                                <i class="fa fa-eye"></i>
                            </a>
                        </div>
                    </div>
                     <div class="flex flex-col flex-grow p-5 text-center">
                        <h3 class="mb-2 text-xl font-bold text-gray-900">
                            <a href="{{ route('product.show', $product->id) }}" class="hover:text-primary">{{ $product->nama_produk }}</a>
                        </h3>
                        <p class="flex-grow text-sm text-gray-600">{{ Str::limit($product->deskripsi, 50) }}</p>
                        <div class="mt-4 text-lg font-semibold text-primary">
                            IDR {{ number_format($product->harga, 0, ',', '.') }}
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-center text-gray-600 col-span-full">Tidak ada produk populer yang ditemukan.</p>
            @endforelse
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // --- Fungsionalitas Tab Kategori ---
        const tabButtons = document.querySelectorAll(".tab-button");
        const tabContents = document.querySelectorAll(".tab-content");
        const activeTabClasses = ['active-tab', 'bg-primary', 'text-white', 'shadow-lg'];
        const defaultTabClasses = ['bg-white', 'text-gray-800', 'shadow-sm'];

        function showCategory(categorySlug) {
            tabContents.forEach(content => {
                content.classList.add("hidden");
                content.classList.remove("grid");
            });

            tabButtons.forEach(button => {
                button.classList.remove(...activeTabClasses);
                button.classList.add(...defaultTabClasses);
            });

            const targetContent = document.getElementById(categorySlug + "-content");
            if (targetContent) {
                targetContent.classList.remove("hidden");
                targetContent.classList.add("grid");
            }

            const activeButton = document.querySelector(`.tab-button[data-category="${categorySlug}"]`);
            if (activeButton) {
                activeButton.classList.add(...activeTabClasses);
                activeButton.classList.remove(...defaultTabClasses);
            }
        }

        tabButtons.forEach(button => {
            button.addEventListener("click", () => {
                const categorySlug = button.dataset.category;
                showCategory(categorySlug);
            });
        });

        // Inisialisasi tab pertama saat halaman dimuat
        if (tabButtons.length > 0) {
            const initialCategory = tabButtons[0].dataset.category;
            showCategory(initialCategory);
        }
    });
</script>
@endpush
