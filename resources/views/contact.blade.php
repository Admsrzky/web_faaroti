@extends('layouts.master')

@section('title', 'Kontak Kami - Faa Roti')
@section('description', 'Hubungi Faa Roti untuk pertanyaan, pesanan khusus, atau umpan balik.')

@section('content')
    <!-- Contact Start -->
    <div class="container px-4 py-32 mx-auto md:px-6">
        <div class="mx-auto mb-10 text-center wow fadeInUp" data-wow-delay="0.1s" style="max-width: 500px;">
            <p class="mb-2 text-lg font-semibold uppercase text-primary">Hubungi Kami</p>
            <h1 class="mb-4 text-3xl font-extrabold text-gray-900 md:text-4xl">Jika Anda Punya Pertanyaan, Silakan Hubungi
                Kami</h1>
        </div>
        <div class="grid grid-cols-1 gap-8 lg:grid-cols-2">
            <div class="wow fadeInUp" data-wow-delay="0.1s">
                <div class="space-y-6">
                    <div class="flex items-start p-4 bg-white border border-gray-200 rounded-lg shadow-md">
                        <div class="flex-shrink-0 mr-4 text-3xl text-primary">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <div>
                            <h5 class="mb-1 text-xl font-semibold text-gray-800">Alamat</h5>
                            <p class="text-gray-700">Seloretno Sidomulyo, Lampung</p>
                        </div>
                    </div>
                    <div class="flex items-start p-4 bg-white border border-gray-200 rounded-lg shadow-md">
                        <div class="flex-shrink-0 mr-4 text-3xl text-primary">
                            <i class="fas fa-phone-alt"></i>
                        </div>
                        <div>
                            <h5 class="mb-1 text-xl font-semibold text-gray-800">Telepon</h5>
                            <p class="text-gray-700">+012 345 67890</p>
                        </div>
                    </div>
                    <div class="flex items-start p-4 bg-white border border-gray-200 rounded-lg shadow-md">
                        <div class="flex-shrink-0 mr-4 text-3xl text-primary">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <div>
                            <h5 class="mb-1 text-xl font-semibold text-gray-800">Email</h5>
                            <p class="text-gray-700">faaroti10@gmail.com</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="wow fadeInUp" data-wow-delay="0.5s">
                <form class="space-y-4">
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        <div>
                            <label for="name" class="sr-only">Nama Anda</label>
                            <input type="text"
                                class="w-full px-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                                id="name" placeholder="Nama Anda">
                        </div>
                        <div>
                            <label for="email" class="sr-only">Email Anda</label>
                            <input type="email"
                                class="w-full px-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                                id="email" placeholder="Email Anda">
                        </div>
                    </div>
                    <div>
                        <label for="subject" class="sr-only">Subjek</label>
                        <input type="text"
                            class="w-full px-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                            id="subject" placeholder="Subjek">
                    </div>
                    <div>
                        <label for="message" class="sr-only">Pesan</label>
                        <textarea
                            class="w-full px-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                            placeholder="Tinggalkan pesan di sini" id="message" style="height: 150px"></textarea>
                    </div>
                    <div>
                        <button
                            class="w-full px-6 py-3 text-lg font-semibold text-white transition-colors duration-300 rounded-md bg-primary hover:bg-primary-dark"
                            type="submit">Kirim Pesan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Contact End -->
@endsection

@push('scripts')
    <!-- Tambahkan script khusus untuk halaman kontak jika diperlukan, misalnya validasi form -->
@endpush
