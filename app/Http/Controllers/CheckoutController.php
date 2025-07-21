<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\Product;
use App\Models\Cart; // Import model Cart
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

// Import Midtrans Library
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Notification;

class CheckoutController extends Controller
{
    public function __construct()
    {
        // Pastikan Anda telah mengatur MIDTRANS_SERVER_KEY dan MIDTRANS_IS_PRODUCTION di file .env Anda
        Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

    /**
     * Menampilkan halaman checkout dengan formulir data pelanggan dan ringkasan keranjang.
     */
    public function index()
    {
        // Pastikan user sudah login
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Anda harus login untuk melanjutkan ke checkout.');
        }

        $userId = Auth::id();
        // Ambil data keranjang dari tabel 'cart' milik user yang sedang login
        $userCartItems = Cart::where('user_id', $userId)->with('product')->get();

        // Jika keranjang kosong, redirect kembali ke halaman keranjang dengan pesan error
        if ($userCartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang belanja Anda kosong. Silakan tambahkan produk terlebih dahulu.');
        }

        $cartItems = [];
        $subtotal = 0;

        foreach ($userCartItems as $cartItem) {
            $product = $cartItem->product;

            if ($product) {
                // Periksa stok produk sebelum checkout
                if ($cartItem->quantity > $product->stok) {
                    return redirect()->route('cart.index')->with('error', 'Stok produk "' . $product->nama_produk . '" tidak mencukupi. Tersedia: ' . $product->stok . ', di keranjang: ' . $cartItem->quantity . '.');
                }

                $itemPrice = $product->harga;
                $itemQuantity = $cartItem->quantity;
                $itemSubtotal = $itemPrice * $itemQuantity;

                $cartItems[] = [
                    'id' => $product->id,
                    'name' => $product->nama_produk,
                    'price' => $itemPrice,
                    'quantity' => $itemQuantity,
                    'image' => asset('storage/' . $product->foto_produk),
                    'description' => $product->deskripsi,
                ];
                $subtotal += $itemSubtotal;
            } else {
                // Log warning jika ada item keranjang yang produknya sudah tidak ada
                Log::warning('Produk dengan ID ' . $cartItem->product_id . ' untuk item keranjang ID ' . $cartItem->id . ' tidak ditemukan di database.');
                // Opsional: Hapus item keranjang yang tidak valid
                // $cartItem->delete();
            }
        }

        $shippingCost = 10000; // Biaya pengiriman tetap
        $grandTotal = $subtotal + $shippingCost;

        return view('order.checkout', compact('cartItems', 'subtotal', 'shippingCost', 'grandTotal'));
    }

    /**
     * Memproses data checkout, menyimpan transaksi, dan menginisiasi pembayaran Midtrans.
     */
    public function process(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'city' => 'required|string|max:255',
            'post_code' => 'required|string|max:10',
            'address' => 'required|string|max:1000',
        ]);

        if (!Auth::check()) {
            return response()->json(['error' => 'Anda harus login untuk memproses checkout.'], 401);
        }

        $userId = Auth::id();
        $userCartItems = Cart::where('user_id', $userId)->with('product')->get();

        if ($userCartItems->isEmpty()) {
            return response()->json(['error' => 'Keranjang belanja Anda kosong. Silakan tambahkan produk terlebih dahulu.'], 400);
        }

        $subtotal = 0;
        $totalQuantity = 0;
        $midtransItemDetails = [];

        DB::beginTransaction();
        try {
            foreach ($userCartItems as $cartItem) {
                $product = $cartItem->product;

                if (!$product) {
                    DB::rollBack();
                    return response()->json(['error' => 'Produk dengan ID ' . $cartItem->product_id . ' tidak ditemukan atau tidak valid.'], 400);
                }

                // Periksa stok produk terakhir sebelum membuat transaksi
                if ($cartItem->quantity > $product->stok) {
                    DB::rollBack();
                    return response()->json(['error' => 'Stok produk "' . $product->nama_produk . '" tidak mencukupi untuk checkout. Tersedia: ' . $product->stok . ', di keranjang: ' . $cartItem->quantity . '.'], 400);
                }

                $itemPrice = $product->harga;
                $itemQuantity = $cartItem->quantity;
                $itemSubtotal = $itemPrice * $itemQuantity;

                $subtotal += $itemSubtotal;
                $totalQuantity += $itemQuantity;

                $midtransItemDetails[] = [
                    'id' => $product->id,
                    'price' => $itemPrice,
                    'quantity' => $itemQuantity,
                    'name' => $product->nama_produk,
                ];
            }

            $shippingCost = 10000;
            $grandTotal = $subtotal + $shippingCost;

            $midtransItemDetails[] = [
                'id' => 'shipping-cost',
                'price' => $shippingCost,
                'quantity' => 1,
                'name' => 'Biaya Pengiriman',
            ];

            $trxId = 'FRT-' . Str::upper(Str::random(10));

            $transaction = Transaction::create([
                'trx_id' => $trxId,
                'user_id' => $userId, // Pastikan user_id disimpan
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'phone' => $validatedData['phone'],
                'city' => $validatedData['city'],
                'post_code' => $validatedData['post_code'],
                'address' => $validatedData['address'],
                'total_quantity' => $totalQuantity,
                'total_sub_total' => $subtotal,
                'grand_total' => $grandTotal,
                'status' => 'pending', // Status awal transaksi (sesuai ENUM)
                'payment_type' => 'qris', // Akan diisi dari webhook Midtrans
                'payment_status' => 'settlement', // Status pembayaran Midtrans (sesuai ENUM)
                'payment_url' => null, // Akan diisi dengan Snap Token atau URL pembayaran
            ]);

            foreach ($userCartItems as $cartItem) {
                $product = $cartItem->product;
                if ($product) {
                    TransactionItem::create([
                        'transaction_id' => $transaction->id,
                        'product_id' => $product->id,
                        'product_name' => $product->nama_produk,
                        'product_photo' => $product->foto_produk,
                        'quantity' => $cartItem->quantity,
                        'price_at_purchase' => $product->harga,
                        'sub_total_item' => $product->harga * $cartItem->quantity,
                    ]);
                }
            }

            // --- MANIPULASI DIMULAI DI SINI (PENGURANGAN STOK & HAPUS KERANJANG SAAT ORDER DIBUAT) ---
            // PERINGATAN: KODE DI BAWAH INI DIMANIPULASI UNTUK TUJUAN PENGUJIAN.
            // JANGAN PERNAH MENGGUNAKAN INI DI LINGKUNGAN PRODUKSI.
            // INI AKAN MENGURANGI STOK DAN MENGHAPUS KERANJANG SEBELUM PEMBAYARAN DIKONFIRMASI.
            Log::info('Condition met for stock reduction and cart deletion (from process method) for transaction ' . $trxId);
            foreach ($userCartItems as $cartItem) {
                $product = Product::find($cartItem->product_id);
                if ($product) {
                    $product->decrement('stok', $cartItem->quantity);
                    Log::info('Stok produk ' . $product->nama_produk . ' (ID: ' . $product->id . ') dikurangi ' . $cartItem->quantity . '. Stok baru: ' . $product->stok . ' (from process method)');
                } else {
                    Log::warning('Produk dengan ID ' . $cartItem->product_id . ' tidak ditemukan saat mencoba mengurangi stok (from process method) untuk transaksi ' . $trxId);
                }
            }
            Cart::where('user_id', $userId)->delete();
            Log::info('Cart items for user ' . $userId . ' deleted (from process method) after transaction ' . $trxId . ' created.');
            // --- MANIPULASI SELESAI DI SINI ---


            $customerDetails = [
                'first_name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'phone' => $validatedData['phone'],
                'address' => $validatedData['address'],
                'city' => $validatedData['city'],
                'postal_code' => $validatedData['post_code'],
            ];

            $transactionDetails = [
                'order_id' => $transaction->trx_id,
                'gross_amount' => $grandTotal,
            ];

            $params = [
                'transaction_details' => $transactionDetails,
                'customer_details' => $customerDetails,
                'item_details' => $midtransItemDetails,
                'callbacks' => [
                    'finish' => route('order.success'), // Halaman sukses setelah pembayaran
                    'unfinish' => route('order.checkout'), // Halaman jika pembayaran belum selesai
                    'error' => route('order.checkout'), // Halaman jika terjadi error pembayaran
                ],
            ];

            $snapToken = Snap::getSnapToken($params);

            // Simpan snapToken ke kolom payment_url di tabel transactions
            $transaction->payment_url = $snapToken;
            $transaction->save();

            DB::commit();
            Log::info('Transaction ' . $trxId . ' successfully created and committed (from process method).');

            return response()->json(['snap_token' => $snapToken]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Midtrans Checkout Error (from process method): ' . $e->getMessage(), ['exception' => $e, 'request_data' => $request->all()]);
            return response()->json(['error' => 'Gagal memproses pembayaran. Silakan coba lagi. Detail: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Handle Midtrans notification (webhook).
     * This method receives payment status updates from Midtrans.
     */
    public function handleMidtransNotification(Request $request)
    {
        Log::debug('Webhook received. Request data: ' . json_encode($request->all()));

        Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);

        try {
            // Coba inisialisasi notifikasi untuk mendapatkan data
            $notification = new Notification();
        } catch (\Exception $e) {
            Log::error('Midtrans Notification Init Error: ' . $e->getMessage(), ['exception' => $e, 'request_data' => $request->all()]);
            return response()->json(['message' => 'Failed to initialize Midtrans Notification'], 500);
        }

        $orderId = $notification->order_id;
        $transaction = Transaction::where('trx_id', $orderId)->first();

        if (!$transaction) {
            Log::error('Midtrans Notification: Order ID ' . $orderId . ' not found in database.');
            return response()->json(['message' => 'Order ID not found'], 404);
        }

        // PERINGATAN: KODE DI BAWAH INI DIMANIPULASI UNTUK TUJUAN PENGUJIAN.
        // JANGAN PERNAH MENGGUNAKAN INI DI LINGKUNGAN PRODUKSI.
        // INI AKAN SECARA PAKSA MENGATUR STATUS PEMBAYARAN DAN JENIS PEMBAYARAN
        // TANPA VERIFIKASI SEBENARNYA DARI MIDTRANS.

        Log::info('--- Starting DB Transaction for Webhook Processing ---'); // Log sebelum DB::beginTransaction
        DB::beginTransaction();
        try {
            // $oldStatus = $transaction->status; // Tidak lagi diperlukan untuk logika stok/keranjang di sini

            // --- MANIPULASI DIMULAI DI SINI ---
            // Logika pembaruan status berdasarkan webhook Midtrans
            $transactionStatus = $notification->transaction_status;
            $fraudStatus = $notification->fraud_status;
            $paymentType = $notification->payment_type;
            $grossAmount = $notification->gross_amount; // Untuk validasi keamanan

            // Pastikan gross_amount yang diterima dari notifikasi sesuai dengan grand_total transaksi
            if ($grossAmount != $transaction->grand_total) {
                Log::error('Midtrans Notification: Gross amount mismatch for Order ID ' . $orderId . '. Expected: ' . $transaction->grand_total . ', Received: ' . $grossAmount);
                DB::rollBack();
                return response()->json(['message' => 'Gross amount mismatch'], 400);
            }

            $transaction->payment_type = $paymentType; // Isi payment_type dari notifikasi

            // Logika pembaruan status (sesuai dengan ENUM 'status' dan 'payment_status' di DB Anda)
            if ($transactionStatus == 'capture') {
                if ($fraudStatus == 'challenge') {
                    $transaction->payment_status = 'challenge';
                    $transaction->status = 'pending'; // Atau 'diproses'
                } else if ($fraudStatus == 'accept') {
                    $transaction->payment_status = 'success';
                    $transaction->status = 'dikirim';
                }
            } else if ($transactionStatus == 'settlement') {
                $transaction->payment_status = 'success';
                $transaction->status = 'dikirim';
            } else if ($transactionStatus == 'pending') {
                $transaction->payment_status = 'pending';
                $transaction->status = 'pending';
            } else if ($transactionStatus == 'deny') {
                $transaction->payment_status = 'deny';
                $transaction->status = 'dibatalkan';
            } else if ($transactionStatus == 'expire') {
                $transaction->payment_status = 'expired';
                $transaction->status = 'dibatalkan';
            } else if ($transactionStatus == 'cancel') {
                $transaction->payment_status = 'cancel';
                $transaction->status = 'dibatalkan';
            } else if ($transactionStatus == 'refund' || $transactionStatus == 'partial_refund') {
                $transaction->payment_status = $transactionStatus;
                $transaction->status = 'dikembalikan';
            } else if ($transactionStatus == 'authorize') {
                $transaction->payment_status = 'authorize';
                $transaction->status = 'pending';
            }
            // --- MANIPULASI SELESAI DI SINI ---

            $transaction->save();
            Log::info('Transaction ' . $orderId . ' status updated by webhook. New payment_status: ' . $transaction->payment_status . ', New status: ' . $transaction->status);

            // Logika pengurangan stok dan penghapusan keranjang TIDAK LAGI DI SINI.
            // Sudah dipindahkan ke metode 'process'.

            DB::commit();
            Log::info('Transaction ' . $orderId . ' (WEBHOOK) successfully committed to DB.');
            return response()->json(['message' => 'Notification handled successfully'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Midtrans Webhook Error for Order ID ' . $orderId . ': ' . $e->getMessage(), ['exception' => $e, 'request_data' => $request->all()]);
            Log::info('DB transaction for Order ID ' . $orderId . ' (WEBHOOK) has been ROLLED BACK.');
            return response()->json(['message' => 'Internal server error'], 500);
        }
    }

    public function success(Request $request)
    {
        $orderId = $request->query('order_id');
        $transactionStatus = $request->query('transaction_status');
        $fraudStatus = $request->query('fraud_status');

        $transaction = Transaction::where('trx_id', $orderId)->first();

        if (!$transaction) {
            return redirect()->route('home')->with('error', 'Transaksi tidak ditemukan.');
        }

        return view('order.success', compact('transaction', 'transactionStatus', 'fraudStatus'));
    }

    public function RiwayatPesanan()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $userId = Auth::id();
        $transactions = Transaction::where('user_id', $userId)
            ->with('items.product')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('order.history', compact('transactions'));
    }
}