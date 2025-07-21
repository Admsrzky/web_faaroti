<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Create the 'cart' table
        Schema::create('cart', function (Blueprint $table) {
            $table->id(); // This will create an auto-incrementing BIGINT UNSIGNED primary key named 'id'
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Foreign key to 'users' table, with cascade delete
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade'); // Foreign key to 'products' table, with cascade delete
            $table->unsignedInteger('quantity'); // Quantity of the product in the cart
            $table->timestamps(); // This will create 'created_at' and 'updated_at' TIMESTAMP columns
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Drop the 'cart' table if it exists
        Schema::dropIfExists('cart');
    }
};
