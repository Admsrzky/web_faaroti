<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <title>@yield('title', 'Faa Roti - Toko Roti Terbaik')</title> {{-- Placeholder untuk title --}}
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <meta name="csrf-token" content="{{ csrf_token() }}"> {{-- PASTIKAN BARIS INI ADA --}}
    <meta content="@yield('keywords', 'Toko Roti, Roti, Kue, Pastry, Makanan Penutup')" name="keywords" /> {{-- Placeholder untuk keywords --}}
    <meta content="@yield('description', 'Faa Roti adalah toko roti terbaik yang menawarkan berbagai macam kue, roti, dan kue kering lezat yang dibuat dengan penuh gairah.')" name="description" /> {{-- Placeholder untuk description --}}

    {{-- <link href="{{ asset('img/favicon.ico') }}" rel="icon" /> --}}

    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&family=Playfair+Display:wght@600;700&display=swap"
        rel="stylesheet" />

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet" />

    <link href="{{ asset('lib/animate/animate.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('lib/owlcarousel/assets/owl.carousel.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('lib/owlcarousel/assets/owl.theme.default.min.css') }}" rel="stylesheet" />

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        // Konfigurasi Tailwind untuk warna kustom agar sesuai dengan template asli
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: "#FEA116",
                        "primary-dark": "#d08512",
                        light: "#F3F6F9",
                        dark: "#0F172B",
                        darker: "#0A101D",
                    },
                    fontFamily: {
                        roboto: ["Roboto", "sans-serif"],
                        playfair: ["Playfair Display", "serif"],
                    },
                },
            },
        };
    </script>
    @stack('styles')
</head>

<body class="bg-white font-roboto">
    <div id="spinner"
        class="fixed inset-0 z-50 flex items-center justify-center transition-opacity duration-500 bg-white opacity-100">
        <div class="w-16 h-16 spinner-grow text-primary animate-spin" role="status"></div>
    </div>

    {{-- HEADER START --}}
    <div class="relative overflow-hidden animate__animated animate__fadeIn" data-wow-delay="0.1s">
        <nav class="fixed top-0 left-0 right-0 z-50 px-4 py-3 bg-white shadow-md lg:py-0 lg:px-12 animate__animated animate__fadeIn"
            data-wow-delay="0.1s">
            <div class="container flex items-center justify-between mx-auto">
                <a href="{{ route('home') }}" class="flex items-center gap-2 ml-4 lg:ml-0">
                    <img src="{{ asset('assets/img/logo2.png') }}" width="90" height="90" alt=""
                        srcset="" />
                    <h1 class="m-0 text-3xl text-primary font-playfair">Faa Roti</h1>
                </a>
                <button type="button" class="text-gray-700 lg:hidden hover:text-primary focus:outline-none"
                    id="navbar-toggler">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16m-7 6h7"></path>
                    </svg>
                </button>
                <div class="items-center justify-center flex-grow hidden lg:flex" id="navbarCollapse">
                    <div class="flex flex-col p-4 mx-auto lg:flex-row lg:p-0">
                        <a href="{{ route('home') }}"
                            class="block px-4 py-2 text-lg font-medium lg:inline-block text-primary hover:text-primary active:text-primary lg:border-b-2 lg:border-primary">Beranda</a>
                        <a href="{{ route('product.index') }}"
                            class="block px-4 py-2 text-lg font-medium text-gray-800 lg:inline-block hover:text-primary">Produk</a>

                        <a href="{{ route('contact') }}"
                            class="block px-4 py-2 text-lg font-medium text-gray-800 lg:inline-block hover:text-primary">Kontak</a>
                    </div>
                    <div class="flex items-center ml-4 space-x-4">
                        <a href="{{ route('cart.index') }}" {{-- Menggunakan route('cart.index') --}}
                            class="relative text-gray-800 transition-colors duration-200 hover:text-primary">
                            <i class="text-xl fa fa-shopping-cart"></i>
                            <span
                                class="absolute flex items-center justify-center w-4 h-4 text-xs text-white rounded-full -top-1 -right-2 bg-primary">
                                {{ $cartItemCount }} {{-- Menampilkan jumlah item di keranjang --}}
                            </span>
                        </a>

                        {{-- AUTENTIKASI DENGAN JETSTREAM --}}
                        @auth {{-- Jika pengguna sudah login --}}
                            <div class="relative flex items-center cursor-pointer group" id="profile-area">
                                @if (Auth::user()->profile_photo_url)
                                    <img src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}"
                                        class="object-cover w-10 h-10 mr-2 border-2 rounded-full border-primary">
                                @else
                                    {{-- Fallback jika tidak ada gambar profil (misal, inisial atau ikon default) --}}
                                    <div
                                        class="flex items-center justify-center mr-2 bg-gray-300 border-2 rounded-full h-9 w-9 border-primary">
                                        <i class="text-lg text-gray-600 fa fa-user"></i>
                                    </div>
                                @endif

                                <div class="absolute hidden bg-white shadow-lg mt-2 min-w-[10rem] rounded-md overflow-hidden z-10 right-0 top-full"
                                    id="profile-dropdown-menu">
                                    {{-- Bagian informasi user di dalam dropdown --}}
                                    <div class="px-4 py-3 border-b border-gray-200">
                                        <p class="text-sm font-semibold text-gray-700">{{ Auth::user()->name }}</p>
                                        <p class="text-xs text-gray-500 truncate">{{ Auth::user()->email }}</p>
                                    </div>

                                    {{-- Menu utama dropdown --}}
                                    <a href="{{ route('profile.show') }}"
                                        class="block px-4 py-2 text-gray-800 hover:bg-gray-100">Profil</a>
                                    <a href="{{ route('order.history') }}"
                                        class="block px-4 py-2 text-gray-800 hover:bg-gray-100">Riwayat Pesanan</a>
                                    {{-- Jika ingin menambahkan link Dashboard, bisa di sini. Route dashboard bawaan Jetstream adalah 'dashboard' --}}
                                    {{-- <a href="{{ route('dashboard') }}" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">Dashboard</a> --}}

                                    <div class="border-t border-gray-200"></div> {{-- Garis pemisah opsional sebelum logout --}}

                                    {{-- Form Logout --}}
                                    <form method="POST" action="{{ route('logout') }}" x-data>
                                        @csrf
                                        <button type="submit"
                                            class="block w-full px-4 py-2 text-left text-gray-800 hover:bg-gray-100">
                                            Keluar
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @else
                            {{-- Jika pengguna belum login --}}
                            <a href="{{ route('login') }}"
                                class="px-5 py-2 text-white transition-colors duration-300 rounded-full bg-primary hover:bg-primary-dark">Masuk</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}"
                                    class="px-5 py-2 text-gray-800 transition-colors duration-300 bg-gray-200 rounded-full hover:bg-gray-300">Daftar</a>
                            @endif
                        @endauth
                        {{-- AKHIR AUTENTIKASI DENGAN JETSTREAM --}}
                    </div>
                </div>
            </div>
        </nav>
    </div>
    {{-- HEADER END --}}

    @yield('content')

    {{-- FOOTER START --}}
    <div class="py-12 mt-24 mb-0 bg-dark text-light footer animate__animated animate__fadeIn" data-wow-delay="0.1s">
        <div class="container py-12 mx-auto">
            <div class="grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-4">
                <div class="lg:col-span-1">
                    <h4 class="mb-4 text-2xl font-bold text-light">Alamat Kantor</h4>
                    <p class="mb-2 text-gray-400">
                        <i class="mr-3 fa fa-map-marker-alt"></i>Seloretno Sidomulyo, Lampung
                    </p>
                    <p class="mb-2 text-gray-400">
                        <i class="mr-3 fa fa-phone-alt"></i>+012 345 67890
                    </p>
                    <p class="mb-2 text-gray-400">
                        <i class="mr-3 fa fa-envelope"></i>faaroti10@gmail.com
                    </p>
                    <div class="flex pt-2 space-x-2">
                        <a class="flex items-center justify-center w-10 h-10 transition-colors duration-300 bg-gray-700 rounded-full text-light hover:bg-primary hover:text-white"
                            href=""><i class="fab fa-twitter"></i></a>
                        <a class="flex items-center justify-center w-10 h-10 transition-colors duration-300 bg-gray-700 rounded-full text-light hover:bg-primary hover:text-white"
                            href=""><i class="fab fa-facebook-f"></i></a>
                        <a class="flex items-center justify-center w-10 h-10 transition-colors duration-300 bg-gray-700 rounded-full text-light hover:bg-primary hover:text-white"
                            href=""><i class="fab fa-youtube"></i></a>
                        <a class="flex items-center justify-center w-10 h-10 transition-colors duration-300 bg-gray-700 rounded-full text-light hover:bg-primary hover:text-white"
                            href=""><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
                <div class="lg:col-span-1">
                    <h4 class="mb-4 text-2xl font-bold text-light">Tautan Cepat</h4>
                    <a class="block py-1 text-gray-400 hover:text-white" href="">Tentang Kami</a>
                    <a class="block py-1 text-gray-400 hover:text-white" href="">Kontak Kami</a>
                    <a class="block py-1 text-gray-400 hover:text-white" href="">Layanan Kami</a>
                    <a class="block py-1 text-gray-400 hover:text-white" href="">Syarat & Ketentuan</a>
                    <a class="block py-1 text-gray-400 hover:text-white" href="">Dukungan</a>
                </div>
                <div class="lg:col-span-1">
                    <h4 class="mb-4 text-2xl font-bold text-light">Tautan Cepat</h4>
                    <a class="block py-1 text-gray-400 hover:text-white" href="">Tentang Kami</a>
                    <a class="block py-1 text-gray-400 hover:text-white" href="">Kontak Kami</a>
                    <a class="block py-1 text-gray-400 hover:text-white" href="">Layanan Kami</a>
                    <a class="block py-1 text-gray-400 hover:text-white" href="">Syarat & Ketentuan</a>
                    <a class="block py-1 text-gray-400 hover:text-white" href="">Dukungan</a>
                </div>
                <div class="lg:col-span-1">
                    <h4 class="mb-4 text-2xl font-bold text-light">Galeri Foto</h4>
                    <div class="grid grid-cols-3 gap-2">
                        <div class="col-span-1">
                            <img class="w-full h-auto p-1 rounded-md bg-light" src="{{ asset('img/product-1.jpg') }}"
                                alt="Gambar" />
                        </div>
                        <div class="col-span-1">
                            <img class="w-full h-auto p-1 rounded-md bg-light" src="{{ asset('img/product-2.jpg') }}"
                                alt="Gambar" />
                        </div>
                        <div class="col-span-1">
                            <img class="w-full h-auto p-1 rounded-md bg-light" src="{{ asset('img/product-3.jpg') }}"
                                alt="Gambar" />
                        </div>
                        <div class="col-span-1">
                            <img class="w-full h-auto p-1 rounded-md bg-light" src="{{ asset('img/product-2.jpg') }}"
                                alt="Gambar" />
                        </div>
                        <div class="col-span-1">
                            <img class="w-full h-auto p-1 rounded-md bg-light" src="{{ asset('img/product-3.jpg') }}"
                                alt="Gambar" />
                        </div>
                        <div class="col-span-1">
                            <img class="w-full h-auto p-1 rounded-md bg-light" src="{{ asset('img/product-1.jpg') }}"
                                alt="Gambar" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="py-4 text-gray-400 bg-darker animate__animated animate__fadeIn" data-wow-delay="0.1s">
        <div class="container mx-auto">
            <div class="flex flex-col items-center justify-between text-center md:flex-row">
                <div class="mb-3 md:mb-0">
                    &copy;2025
                    <a href="#" class="text-white hover:text-primary">Faa Roti</a>, Hak
                    Cipta Dilindungi.
                </div>
            </div>
        </div>
    </div>
    {{-- FOOTER END --}}

    <a href="#"
        class="fixed z-50 flex items-center justify-center text-white transition-colors duration-300 rounded-full shadow-lg bottom-8 right-8 w-14 h-14 bg-primary hover:bg-primary-dark"><i
            class="text-2xl bi bi-arrow-up"></i></a>

    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('lib/wow/wow.min.js') }}"></script>
    <script src="{{ asset('lib/easing/easing.min.js') }}"></script>
    <script src="{{ asset('lib/waypoints/waypoints.min.js') }}"></script>
    <script src="{{ asset('lib/counterup/counterup.min.js') }}"></script>
    <script src="{{ asset('lib/owlcarousel/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('js/main.js') }}"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const spinner = document.getElementById("spinner");
            if (spinner) {
                setTimeout(() => {
                    spinner.classList.remove("opacity-100");
                    spinner.classList.add("opacity-0");
                    setTimeout(() => {
                        spinner.classList.add("hidden");
                    }, 500);
                }, 100);
            }

            const navbarToggler = document.getElementById("navbar-toggler");
            const navbarCollapse = document.getElementById("navbarCollapse");
            if (navbarToggler && navbarCollapse) {
                navbarToggler.addEventListener("click", function() {
                    navbarCollapse.classList.toggle("hidden");
                });
            }

            // JavaScript untuk mengelola dropdown profil
            const profileArea = document.getElementById('profile-area');
            const profileDropdownMenu = document.getElementById('profile-dropdown-menu');

            if (profileArea && profileDropdownMenu) {
                profileArea.addEventListener('click', function() {
                    profileDropdownMenu.classList.toggle('hidden');
                });

                // Tutup dropdown jika klik di luar area dropdown
                document.addEventListener('click', function(event) {
                    if (!profileArea.contains(event.target) && !profileDropdownMenu.contains(event
                            .target)) {
                        profileDropdownMenu.classList.add('hidden');
                    }
                });
            }
        });
    </script>
    @stack('scripts')
</body>

</html>
