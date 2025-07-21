<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();

            // Informasi Pembeli/Pengiriman
            $table->string('trx_id')->unique(); // ID Transaksi Internal Anda, pastikan unik
            $table->string('name');
            $table->string('email');
            $table->string('phone');
            $table->string('city');
            $table->string('post_code');
            $table->text('address');

            // Agregat dari transaction_items
            $table->unsignedBigInteger('total_quantity')->default(0); // Total kuantitas semua produk dalam transaksi ini
            $table->unsignedBigInteger('total_sub_total')->default(0); // Total harga produk sebelum diskon/ongkir
            $table->unsignedBigInteger('grand_total')->default(0); // Total akhir yang dibayar

            // Status Order/Pengiriman (tetap sesuai yang Anda inginkan)
            $table->enum('status', ['pending', 'dikirim', 'selesai'])->default('pending');

            // Kolom Khusus Midtrans
            $table->string('midtrans_transaction_id')->nullable()->unique(); // ID transaksi dari Midtrans
            $table->string('midtrans_gross_amount')->nullable(); // Total pembayaran yang diterima Midtrans (seringnya sama dengan grand_total)
            $table->string('midtrans_payment_type')->nullable(); // bank_transfer, credit_card, gopay, shopeepay, etc.
            $table->enum('midtrans_payment_status', [
                'pending',      // Pembayaran belum selesai di Midtrans
                'capture',      // Khusus CC: dana berhasil ditangkap
                'settlement',   // Pembayaran sukses
                'deny',         // Pembayaran ditolak
                'cancel',       // Pembayaran dibatalkan
                'expire',       // Pembayaran kedaluwarsa (VA)
                'refund',       // Pembayaran dikembalikan
                'partial_refund', // Sebagian pembayaran dikembalikan
                'authorize',    // Khusus CC: dana diotorisasi tapi belum ditangkap
            ])->default('pending');
            $table->string('midtrans_fraud_status')->nullable(); // accept, challenge, deny
            $table->string('midtrans_va_number')->nullable(); // Nomor Virtual Account jika pembayaran via VA
            $table->string('midtrans_bank')->nullable(); // Nama bank jika pembayaran via transfer/VA
            $table->dateTime('midtrans_expiry_time')->nullable(); // Waktu kadaluarsa pembayaran Midtrans
            $table->text('midtrans_payment_url')->nullable(); // URL pembayaran (untuk redirect)

            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            // $table->foreignId('user_id')->constrained()->cascadeOnDelete(); // Gunakan ini jika user wajib ada

            $table->timestamps(); // Memperbaiki typo di sini
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
