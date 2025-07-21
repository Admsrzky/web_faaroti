@extends('layouts.master')

@section('title', 'Checkout - Faa Roti')
@section('description', 'Lengkapi detail pengiriman dan lanjutkan ke pembayaran untuk pesanan Anda di Faa Roti.')

@section('content')
    <div class="container px-4 py-12 mx-auto md:px-6">
        <div class="grid grid-cols-1 gap-8 lg:grid-cols-2">
            <!-- Formulir Informasi Pelanggan -->
            <div class="lg:col-span-1">
                <div class="p-6 bg-white rounded-lg shadow-md animate__animated animate__fadeInUp" data-wow-delay="0.1s">
                    <h2 class="mb-6 text-2xl font-bold text-gray-900">Informasi Pengiriman</h2>
                    <form id="checkout-form" action="{{ route('checkout.process') }}" method="POST">
                        @csrf

                        <div class="mb-4">
                            <label for="name" class="block mb-2 text-sm font-bold text-gray-700">Nama Lengkap:</label>
                            <input type="text" id="name" name="name"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('name') border-red-500 @enderror"
                                value="{{ old('name', Auth::user()->name ?? '') }}" required autocomplete="name">
                            @error('name')
                                <p class="text-xs italic text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="email" class="block mb-2 text-sm font-bold text-gray-700">Email:</label>
                            <input type="email" id="email" name="email"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('email') border-red-500 @enderror"
                                value="{{ old('email', Auth::user()->email ?? '') }}" required autocomplete="email">
                            @error('email')
                                <p class="text-xs italic text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="phone" class="block mb-2 text-sm font-bold text-gray-700">Nomor Telepon:</label>
                            <input type="tel" id="phone" name="phone"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('phone') border-red-500 @enderror"
                                value="{{ old('phone', Auth::user()->phone ?? '') }}" required autocomplete="tel">
                            @error('phone')
                                <p class="text-xs italic text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="address" class="block mb-2 text-sm font-bold text-gray-700">Alamat Lengkap:</label>
                            <textarea id="address" name="address" rows="3"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('address') border-red-500 @enderror"
                                required autocomplete="street-address">{{ old('address', Auth::user()->address ?? '') }}</textarea>
                            @error('address')
                                <p class="text-xs italic text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 gap-4 mb-6 md:grid-cols-2">
                            <div>
                                <label for="city" class="block mb-2 text-sm font-bold text-gray-700">Kota:</label>
                                <input type="text" id="city" name="city"
                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('city') border-red-500 @enderror"
                                    value="{{ old('city', Auth::user()->city ?? '') }}" required
                                    autocomplete="address-level2">
                                @error('city')
                                    <p class="text-xs italic text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="post_code" class="block mb-2 text-sm font-bold text-gray-700">Kode Pos:</label>
                                <input type="text" id="post_code" name="post_code"
                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('post_code') border-red-500 @enderror"
                                    value="{{ old('post_code', Auth::user()->post_code ?? '') }}" required
                                    autocomplete="postal-code">
                                @error('post_code')
                                    <p class="text-xs italic text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <button type="submit" id="pay-button"
                            class="w-full px-6 py-3 text-lg font-semibold text-white transition-colors duration-300 rounded-full bg-primary hover:bg-primary-dark">
                            Lanjutkan ke Pembayaran
                        </button>
                    </form>
                </div>
            </div>

            <!-- Ringkasan Pesanan -->
            <div class="lg:col-span-1">
                <div class="p-6 bg-white rounded-lg shadow-md animate__animated animate__fadeInUp" data-wow-delay="0.3s">
                    <h2 class="mb-6 text-2xl font-bold text-gray-900">Ringkasan Pesanan</h2>

                    <!-- Daftar Item Keranjang -->
                    @forelse ($cartItems as $item)
                        <div class="flex items-center py-3 border-b border-gray-200">
                            <div class="flex-shrink-0 w-20 h-20 overflow-hidden border border-gray-200 rounded-md">
                                <img src="{{ $item['image'] ?? asset('img/placeholder.jpg') }}" alt="{{ $item['name'] }}"
                                    class="object-cover object-center w-full h-full" />
                            </div>
                            <div class="flex-1 ml-4">
                                <h3 class="text-base font-medium text-gray-900">{{ $item['name'] }}</h3>
                                <p class="text-sm text-gray-500">{{ $item['description'] ?? '' }}</p>
                                <p class="text-sm text-gray-700">
                                    {{ $item['quantity'] }} x Rp {{ number_format($item['price'], 0, ',', '.') }}
                                </p>
                            </div>
                            <p class="text-base font-medium text-gray-900">
                                Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}
                            </p>
                        </div>
                    @empty
                        <p class="text-center text-gray-600">Keranjang Anda kosong.</p>
                    @endforelse

                    <!-- Total Harga -->
                    <div class="py-4 mt-4 border-t border-gray-200">
                        <div class="flex justify-between mb-2 text-base font-medium text-gray-900">
                            <p>Subtotal</p>
                            <p>Rp {{ number_format($subtotal, 0, ',', '.') }}</p>
                        </div>
                        <div class="flex justify-between mb-2 text-base font-medium text-gray-900">
                            <p>Ongkos Kirim</p>
                            <p>Rp {{ number_format($shippingCost, 0, ',', '.') }}</p>
                        </div>
                        <div
                            class="flex justify-between pt-4 mt-4 text-xl font-bold text-gray-900 border-t border-gray-300">
                            <p>Total</p>
                            <p>Rp {{ number_format($grandTotal, 0, ',', '.') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    {{-- Midtrans Snap JS Library --}}
    {{-- PENTING: Pindahkan ini ke sini agar hanya dimuat di halaman checkout --}}
    <script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>

    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function() {
            const payButton = document.getElementById('pay-button');
            const checkoutForm = document.getElementById('checkout-form');

            if (payButton && checkoutForm) {
                payButton.addEventListener('click', function(event) {
                    event.preventDefault(); // Mencegah submit form default

                    // Tampilkan loading indicator
                    payButton.textContent = 'Memproses...';
                    payButton.disabled = true;

                    // Ambil data dari form
                    const formData = new FormData(checkoutForm);
                    const jsonData = {};
                    formData.forEach((value, key) => {
                        jsonData[key] = value;
                    });

                    // Kirim data formulir via AJAX
                    fetch(checkoutForm.action, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                    .getAttribute('content')
                            },
                            body: JSON.stringify(jsonData)
                        })
                        .then(response => {
                            // Periksa jika respons bukan JSON (misal, validasi gagal dengan redirect)
                            const contentType = response.headers.get("content-type");
                            if (contentType && contentType.indexOf("application/json") !== -1) {
                                return response.json();
                            } else {
                                // Jika bukan JSON, mungkin ada error validasi yang mengembalikan HTML
                                // Atau error server lainnya. Reload halaman untuk melihat error.
                                window.location.reload();
                                throw new Error(
                                    'Response was not JSON, likely validation error or server issue.'
                                );
                            }
                        })
                        .then(data => {
                            if (data.snap_token) {
                                // Panggil Midtrans Snap Pop-up
                                snap.pay(data.snap_token, {
                                    onSuccess: function(result) {
                                        // alert("Pembayaran berhasil!"); // Hindari alert, gunakan redirect
                                        console.log(result);
                                        // Arahkan ke halaman sukses, sertakan order_id untuk verifikasi
                                        // Penting: Redirect ke halaman sukses Anda, yang akan menampilkan status dari webhook
                                        window.location.href =
                                            "{{ route('order.success') }}?order_id=" +
                                            result.order_id + "&transaction_status=" +
                                            result.transaction_status + "&fraud_status=" +
                                            result.fraud_status;
                                    },
                                    onPending: function(result) {
                                        // alert("Pembayaran tertunda!"); // Hindari alert
                                        console.log(result);
                                        // Penting: Redirect ke halaman sukses Anda, yang akan menampilkan status dari webhook
                                        window.location.href =
                                            "{{ route('order.success') }}?order_id=" +
                                            result.order_id + "&transaction_status=" +
                                            result.transaction_status + "&fraud_status=" +
                                            result.fraud_status;
                                    },
                                    onError: function(result) {
                                        // alert("Pembayaran gagal!"); // Hindari alert
                                        console.log(result);
                                        alert(
                                            'Pembayaran gagal. Silakan coba lagi.'
                                        ); // Tetap berikan feedback
                                        payButton.textContent = 'Lanjutkan ke Pembayaran';
                                        payButton.disabled = false;
                                    },
                                    onClose: function() {
                                        // alert('Anda menutup pop-up tanpa menyelesaikan pembayaran.'); // Hindari alert
                                        console.log('Pop-up pembayaran ditutup.');
                                        alert(
                                            'Anda menutup pop-up tanpa menyelesaikan pembayaran. Status transaksi mungkin masih pending.'
                                        ); // Feedback
                                        payButton.textContent = 'Lanjutkan ke Pembayaran';
                                        payButton.disabled = false;
                                    }
                                });
                            } else if (data.error) {
                                alert('Error: ' + data.error);
                                console.error('Backend Error:', data.error);
                                payButton.textContent = 'Lanjutkan ke Pembayaran';
                                payButton.disabled = false;
                            }
                        })
                        .catch(error => {
                            console.error('Fetch Error:', error);
                            alert('Terjadi kesalahan saat memproses pembayaran. Silakan coba lagi.');
                            payButton.textContent = 'Lanjutkan ke Pembayaran';
                            payButton.disabled = false;
                        });
                });
            }
        });
    </script>
@endpush
