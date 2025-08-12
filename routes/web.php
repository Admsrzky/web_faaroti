<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CheckoutController;
// use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken; // Tidak perlu diimpor di sini kecuali Anda menggunakannya secara langsung

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route untuk halaman utama dan produk
Route::get('/', [ProductController::class, 'showProductCategories'])->name('home');
Route::get('/products', [ProductController::class, 'index'])->name('product.index');
Route::get('/produk-kategori', [ProductController::class, 'showProductCategories'])->name('product.categories');
// Diselaraskan: Menggunakan 'product.show' sebagai nama route untuk detail produk
Route::get('/products/{product}', [ProductController::class, 'show'])->name('product.show');

// Route untuk halaman kontak
Route::get('/contact', [ProductController::class, 'contact'])->name('contact');

// Route untuk halaman keranjang (tidak memerlukan otentikasi langsung, tapi bisa di-redirect jika kosong)
// Diselaraskan: Menggunakan 'cart.index' sebagai nama route
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');


// Grup route yang memerlukan otentikasi
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    // Route Dashboard (mengarah ke ProductController, sesuaikan jika Anda memiliki dashboard lain)
    Route::get('/dashboard', [ProductController::class, 'showProductCategories'])->name('dashboard');

    // Route untuk operasi keranjang yang memerlukan otentikasi
    Route::post('/cart/add', [CartController::class, 'addToCart'])->name('cart.add');
    Route::put('/cart/{cartItem}', [CartController::class, 'updateQuantity'])->name('cart.updateQuantity');
    Route::delete('/cart/{cartItem}', [CartController::class, 'remove'])->name('cart.remove');

    // Route untuk proses checkout yang memerlukan otentikasi
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('order.checkout');
    Route::post('/checkout/process', [CheckoutController::class, 'process'])->name('checkout.process');

    // Route untuk riwayat pesanan (hanya satu, di dalam middleware auth)
    Route::get('/riwayat-pesanan', [CheckoutController::class, 'RiwayatPesanan'])->name('order.history');
    Route::patch('/order/{transaction}/complete', [CheckoutController::class, 'complete'])->name('order.complete');
});


// Route yang tidak memerlukan otentikasi (webhook Midtrans dan halaman sukses)
// Penting: Webhook harus bisa diakses publik oleh Midtrans
Route::post('/midtrans/notification', [CheckoutController::class, 'handleMidtransNotification'])->name('midtrans.notification');
Route::get('/order/success', [CheckoutController::class, 'success'])->name('order.success');
