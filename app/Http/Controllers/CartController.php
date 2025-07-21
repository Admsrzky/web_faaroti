<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    /**
     * Menampilkan halaman keranjang belanja dengan item-item pengguna.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Pastikan pengguna sudah login
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Anda harus login untuk melihat keranjang belanja Anda.');
        }

        $userId = Auth::id();

        // Ambil semua item keranjang untuk pengguna yang login, dengan eager loading produk terkait
        // Pastikan relasi 'product' di model Cart sudah benar
        $cartItems = Cart::where('user_id', $userId)->with('product')->get();

        $subtotal = 0;
        foreach ($cartItems as $item) {
            // Pastikan produk ada dan harga produk valid sebelum menghitung subtotal
            if ($item->product && is_numeric($item->product->harga) && is_numeric($item->quantity)) {
                $subtotal += $item->quantity * $item->product->harga;
            } else {
                // Opsional: Log error jika ada item keranjang tanpa produk atau data harga/kuantitas tidak valid
                \Illuminate\Support\Facades\Log::warning("Cart item ID {$item->id} has missing product or invalid price/quantity data.");
            }
        }

        $shippingCost = 10000;

        $total = $subtotal + $shippingCost;

        return view('cart.show', compact('cartItems', 'subtotal', 'shippingCost', 'total'));
    }

    /**
     * Menambahkan produk ke keranjang.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function addToCart(Request $request)
    {
        // 1. Validasi input
        $request->validate([
            'product_id' => 'required|exists:products,id', // Pastikan product_id ada di tabel products
            'quantity' => 'required|integer|min:1', // Kuantitas harus angka, minimal 1
        ]);

        // 2. Pastikan pengguna sudah login
        if (!Auth::check()) {
            // Jika belum login, arahkan ke halaman login atau tangani sesuai kebijakan aplikasi Anda
            return redirect()->route('login')->with('error', 'Anda harus login untuk menambahkan produk ke keranjang.');
        }

        $userId = Auth::id(); // Dapatkan ID pengguna yang sedang login
        $productId = $request->product_id;
        $requestedQuantity = $request->quantity;

        // 3. Dapatkan informasi produk
        $product = Product::find($productId);

        // Periksa apakah produk ditemukan dan stoknya mencukupi
        if (!$product) {
            return back()->with('error', 'Produk tidak ditemukan.');
        }

        // 4. Periksa apakah produk sudah ada di keranjang pengguna
        $cartItem = Cart::where('user_id', $userId)
            ->where('product_id', $productId)
            ->first();

        if ($cartItem) {
            // Jika produk sudah ada, perbarui kuantitasnya
            $newQuantity = $cartItem->quantity + $requestedQuantity;

            // Periksa kembali stok setelah penambahan
            if ($product->stok < $newQuantity) {
                return back()->with('error', 'Penambahan kuantitas melebihi stok yang tersedia. Stok saat ini: ' . $product->stok);
            }

            $cartItem->quantity = $newQuantity;
            $cartItem->save();
            return back()->with('success', 'Kuantitas produk di keranjang berhasil diperbarui!');
        } else {
            // Jika produk belum ada, tambahkan sebagai item baru di keranjang
            // Periksa stok sebelum membuat item baru
            if ($product->stok < $requestedQuantity) {
                return back()->with('error', 'Stok produk tidak mencukupi untuk jumlah yang diminta.');
            }

            Cart::create([
                'user_id' => $userId,
                'product_id' => $productId,
                'quantity' => $requestedQuantity,
            ]);
            return back()->with('success', 'Produk berhasil ditambahkan ke keranjang!');
        }
    }

    /**
     * Memperbarui kuantitas item di keranjang.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Cart  $cartItem
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateQuantity(Request $request, Cart $cartItem)
    {
        // Pastikan pengguna memiliki item keranjang ini
        if (Auth::id() !== $cartItem->user_id) {
            return back()->with('error', 'Anda tidak memiliki izin untuk mengubah item keranjang ini.');
        }

        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $newQuantity = $request->quantity;
        $product = $cartItem->product; // Ambil produk terkait

        if (!$product) {
            return back()->with('error', 'Produk terkait tidak ditemukan.');
        }

        // Periksa stok
        if ($product->stok < $newQuantity) {
            return back()->with('error', 'Kuantitas yang diminta melebihi stok yang tersedia. Stok saat ini: ' . $product->stok);
        }

        $cartItem->quantity = $newQuantity;
        $cartItem->save();

        // Redirect kembali ke halaman keranjang untuk memuat ulang data dengan subtotal yang diperbarui
        return redirect()->route('cart.index')->with('success', 'Kuantitas keranjang berhasil diperbarui!');
    }

    /**
     * Menghapus item dari keranjang.
     *
     * @param  \App\Models\Cart  $cartItem
     * @return \Illuminate\Http\RedirectResponse
     */
    public function remove(Cart $cartItem)
    {
        // Pastikan pengguna memiliki item keranjang ini
        if (Auth::id() !== $cartItem->user_id) {
            return back()->with('error', 'Anda tidak memiliki izin untuk menghapus item keranjang ini.');
        }

        $cartItem->delete();

        return back()->with('success', 'Produk berhasil dihapus dari keranjang.');
    }
}
