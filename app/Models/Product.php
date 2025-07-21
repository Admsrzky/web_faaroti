<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'products';
    protected $fillable = [
        'nama_produk',
        'deskripsi',
        'foto_produk',
        'harga',
        'stok',
        'is_popular',
        'category_id',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}