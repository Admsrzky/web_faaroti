<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <title>@yield('title', 'Faa Roti - Toko Roti Terbaik')</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta content="@yield('keywords', 'Toko Roti, Roti, Kue, Pastry, Makanan Penutup')" name="keywords" />
    <meta content="@yield('description', 'Faa Roti adalah toko roti terbaik yang menawarkan berbagai macam kue, roti, dan kue kering lezat yang dibuat dengan penuh gairah.')" name="description" />

    {{-- Favicon --}}
    {{-- <link href="{{ asset('img/favicon.ico') }}" rel="icon" /> --}}

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet" />

    {{-- Icon Libraries --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet" />

    {{-- Libraries Stylesheet --}}
    <link href="{{ asset('lib/animate/animate.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('lib/owlcarousel/assets/owl.carousel.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('lib/owlcarousel/assets/owl.theme.default.min.css') }}" rel="stylesheet" />

    {{-- Tailwind & Custom Config --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
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

    {{-- Alpine.js untuk Interaksi Elegan --}}
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    @stack('styles')
</head>

<body class="bg-white font-roboto">
    {{-- Spinner --}}
    <div id="spinner" class="fixed inset-0 z-[100] flex items-center justify-center transition-opacity duration-500 bg-white opacity-100">
        <div class="w-16 h-16 spinner-grow text-primary animate-spin" role="status"></div>
    </div>

    {{-- HEADER START (Disederhanakan untuk Mobile) --}}
    <header class="relative animate__animated animate__fadeIn" data-wow-delay="0.1s">
        <nav class="fixed top-0 left-0 right-0 z-50 px-4 py-3 bg-white shadow-md lg:py-0 lg:px-12">
            <div class="container flex items-center justify-between mx-auto">
                {{-- Logo --}}
                <a href="{{ route('home') }}" class="flex items-center flex-shrink-0 gap-2">
                    <img src="{{ asset('assets/img/logo2.png') }}" width="90" height="90" alt="Faa Roti Logo" />
                    <h1 class="hidden m-0 text-3xl sm:block text-primary font-playfair">Faa Roti</h1>
                </a>

                {{-- Desktop Menu & Ikon Kanan (Profil, Keranjang) --}}
                <div class="flex items-center">
                    {{-- Menu Teks untuk Desktop --}}
                    <div class="hidden lg:flex lg:space-x-2 lg:mx-8">
                        <a href="{{ route('home') }}" class="px-4 py-2 text-lg font-medium transition-colors {{ request()->routeIs('home') ? 'text-primary border-b-2 border-primary' : 'text-gray-800' }} hover:text-primary">Beranda</a>
                        <a href="{{ route('product.index') }}" class="px-4 py-2 text-lg font-medium transition-colors {{ request()->routeIs('product.index') ? 'text-primary border-b-2 border-primary' : 'text-gray-800' }} hover:text-primary">Produk</a>
                        <a href="{{ route('contact') }}" class="px-4 py-2 text-lg font-medium transition-colors {{ request()->routeIs('contact') ? 'text-primary border-b-2 border-primary' : 'text-gray-800' }} hover:text-primary">Kontak</a>
                    </div>

                    {{-- Ikon Keranjang & Profil --}}
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('cart.index') }}" class="relative text-gray-800 transition-colors duration-200 hover:text-primary">
                            <i class="text-xl fa fa-shopping-cart"></i>
                            <span class="absolute flex items-center justify-center w-5 h-5 text-xs text-white rounded-full -top-2 -right-3 bg-primary">
                                {{ $cartItemCount ?? 0 }}
                            </span>
                        </a>

                        @auth
                            <div x-data="{ open: false }" class="relative">
                                <button @click="open = !open" class="flex items-center appearance-none focus:outline-none">
                                    @if (Auth::user()->profile_photo_url)
                                        <img src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" class="object-cover w-10 h-10 border-2 rounded-full border-primary">
                                    @else
                                        <div class="flex items-center justify-center bg-gray-200 border-2 rounded-full w-9 h-9 border-primary">
                                            <i class="text-lg text-gray-600 fa fa-user"></i>
                                        </div>
                                    @endif
                                </button>

                                <div x-show="open" @click.away="open = false" x-transition class="absolute right-0 w-56 mt-2 origin-top-right bg-white rounded-md shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none" style="display: none;">
                                    <div class="py-1">
                                        <div class="px-4 py-3 border-b border-gray-200">
                                            <p class="text-sm font-semibold text-gray-800">Halo, {{ Str::words(Auth::user()->name, 1, '') }}</p>
                                            <p class="text-xs text-gray-500 truncate">{{ Auth::user()->email }}</p>
                                        </div>
                                        <a href="{{ route('profile.show') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 transition-colors hover:bg-gray-100"><i class="w-5 mr-3 text-gray-500 fas fa-user-circle"></i>Profil</a>
                                        <a href="{{ route('order.history') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 transition-colors hover:bg-gray-100"><i class="w-5 mr-3 text-gray-500 fas fa-history"></i>Riwayat Pesanan</a>
                                        <div class="border-t border-gray-100"></div>
                                        <form method="POST" action="{{ route('logout') }}"><@csrf<button type="submit" class="flex items-center w-full px-4 py-2 text-sm text-left text-gray-700 transition-colors hover:bg-gray-100"><i class="w-5 mr-3 text-gray-500 fas fa-sign-out-alt"></i>Keluar</button></form>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="flex items-center">
                                <a href="{{ route('login') }}" class="px-5 py-2 text-sm text-white transition-colors duration-300 rounded-md bg-primary hover:bg-primary-dark">Masuk</a>
                            </div>
                        @endauth
                    </div>
                </div>
            </div>
        </nav>
    </header>
    {{-- HEADER END --}}

    {{-- Konten utama dengan padding bawah untuk memberi ruang bagi bottom nav --}}
    <main class="pt-24 pb-20">
        @yield('content')
    </main>

    {{-- BOTTOM NAVIGATION BAR (KHUSUS MOBILE) --}}
    <div class="fixed bottom-0 left-0 right-0 z-40 block bg-white border-t border-gray-200 shadow-t-md lg:hidden">
        <div class="flex items-center justify-around h-16">
            <a href="{{ route('home') }}" class="flex flex-col items-center justify-center w-full transition-colors {{ request()->routeIs('home') ? 'text-primary' : 'text-gray-500' }} hover:text-primary">
                <i class="text-xl fas fa-home"></i>
                <span class="mt-1 text-xs font-medium">Beranda</span>
            </a>
            <a href="{{ route('product.index') }}" class="flex flex-col items-center justify-center w-full transition-colors {{ request()->routeIs('product.index*') ? 'text-primary' : 'text-gray-500' }} hover:text-primary">
                <i class="text-xl fas fa-th-large"></i>
                <span class="mt-1 text-xs font-medium">Produk</span>
            </a>
            <a href="{{ route('contact') }}" class="flex flex-col items-center justify-center w-full transition-colors {{ request()->routeIs('contact') ? 'text-primary' : 'text-gray-500' }} hover:text-primary">
                <i class="text-xl fas fa-envelope"></i>
                <span class="mt-1 text-xs font-medium">Kontak</span>
            </a>
        </div>
    </div>


    {{-- Back to Top Button --}}
    <a href="#" class="fixed z-50 flex items-center justify-center text-white transition-opacity duration-300 rounded-full shadow-lg opacity-0 bottom-20 right-5 w-14 h-14 bg-primary hover:bg-primary-dark lg:bottom-8 lg:right-8" id="back-to-top"><i class="text-2xl bi bi-arrow-up"></i></a>

    {{-- JS Libraries & Custom Scripts --}}
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
            // Spinner
            const spinner = document.getElementById("spinner");
            if (spinner) {
                setTimeout(() => {
                    spinner.classList.add("opacity-0");
                    setTimeout(() => spinner.style.display = 'none', 500);
                }, 100);
            }

            // Back to Top Button
            const backToTopButton = document.getElementById('back-to-top');
            if (backToTopButton) {
                window.addEventListener('scroll', () => {
                    if (window.pageYOffset > 300) {
                        backToTopButton.classList.add('opacity-100');
                    } else {
                        backToTopButton.classList.remove('opacity-100');
                    }
                });
            }
        });
    </script>
    @stack('scripts')
</body>
</html>
