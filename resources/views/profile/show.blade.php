@extends('layouts.master') {{-- Ganti 'layouts.master' jika nama file layout utama Anda berbeda --}}

@section('title', 'Profil Pengguna - Faa Roti')
@section('description', 'Kelola informasi profil dan kata sandi Anda di Faa Roti.')
@section('keywords', 'profil, akun, kata sandi, Faa Roti')


@section('content')
    <div class="container px-4 py-32 mx-auto lg:px-8"> {{-- Hapus mt-24 karena sudah ada py-6 di page-header --}}
        <div class="max-w-4xl mx-auto space-y-10"> {{-- Sesuaikan lebar max dan jarak antar komponen --}}
            @if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::updateProfileInformation()))
                <div class="p-6 rounded-lg shadow-lg bg-gray-50 md:p-8 wow fadeInUp" data-wow-delay="0.2s">
                    {{-- DIUBAH: bg-white menjadi bg-gray-50 --}}
                    <h3 class="pb-3 mb-4 text-2xl border-b font-playfair text-dark">Informasi Profil</h3>
                    <p class="mb-6 text-gray-600">Perbarui informasi akun dan alamat email Anda.</p>
                    @livewire('profile.update-profile-information-form')
                </div>
            @endif

            @if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::updatePasswords()))
                <div class="p-6 rounded-lg shadow-lg bg-gray-50 md:p-8 wow fadeInUp" data-wow-delay="0.3s">
                    {{-- DIUBAH: bg-white menjadi bg-gray-50 --}}
                    <h3 class="pb-3 mb-4 text-2xl border-b font-playfair text-dark">Perbarui Kata Sandi</h3>
                    <p class="mb-6 text-gray-600">Pastikan akun Anda menggunakan kata sandi yang panjang dan acak agar tetap
                        aman.</p>
                    @livewire('profile.update-password-form')
                </div>
            @endif

            @if (Laravel\Jetstream\Jetstream::hasAccountDeletionFeatures())
                <div class="p-6 rounded-lg shadow-lg bg-gray-50 md:p-8 wow fadeInUp" data-wow-delay="0.4s">
                    {{-- DIUBAH: bg-white menjadi bg-gray-50 --}}
                    <h3 class="pb-3 mb-4 text-2xl border-b font-playfair text-dark">Hapus Akun</h3>
                    <p class="mb-6 text-gray-600">Hapus akun Anda secara permanen.</p>
                    <div class="mt-10 sm:mt-0">
                        @livewire('profile.delete-user-form')
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
