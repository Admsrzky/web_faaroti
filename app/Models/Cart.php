<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $table = 'cart';

    protected $fillable = [
        'user_id',
        'product_id',
        'quantity',
    ];

    /**
     * Relasi dengan model User.
     * Sebuah item keranjang dimiliki oleh satu User.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi dengan model Product.
     * Sebuah item keranjang memiliki satu Product.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
