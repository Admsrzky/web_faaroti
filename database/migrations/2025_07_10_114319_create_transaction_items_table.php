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
        Schema::create('transaction_items', function (Blueprint $table) {
            $table->id();

            // Foreign key ke tabel 'transactions'
            $table->foreignId('transaction_id')
                ->constrained('transactions') // Menghubungkan ke tabel 'transactions'
                ->cascadeOnDelete(); // Jika transaksi dihapus, itemnya juga ikut dihapus

            // Foreign key ke tabel 'products'
            $table->foreignId('product_id')
                ->constrained('products') // Menghubungkan ke tabel 'products'
                ->cascadeOnDelete(); // Opsional: Jika produk dihapus, item transaksinya juga dihapus. Atau bisa pakai ->restrictOnDelete() jika ingin mencegah penghapusan produk yang masih ada di transaksi.

            $table->string('product_name'); // Simpan nama produk untuk histori, karena produk bisa berubah nama
            $table->string('product_photo')->nullable(); // Simpan foto produk untuk histori
            $table->unsignedInteger('quantity'); // Kuantitas produk ini dalam transaksi
            $table->unsignedBigInteger('price_at_purchase'); // Harga produk saat dibeli (penting untuk riwayat)
            $table->unsignedBigInteger('sub_total_item'); // sub_total_item = quantity * price_at_purchase

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaction_items');
    }
};
