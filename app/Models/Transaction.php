<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// use App\Enums\OrderStatus;

class Transaction extends Model
{
    use HasFactory;

    // Sesuaikan dengan kolom yang bisa diisi massal
    protected $fillable = [
        'trx_id',
        'name',
        'email',
        'phone',
        'city',
        'post_code',
        'address',
        'total_quantity',
        'total_sub_total',
        'grand_total',
        'status',
        'payment_type',
        'payment_status',
        'payment_url',
        'user_id'
    ];

    // protected $casts = [
    //     'midtrans_expiry_time' => 'datetime',
    //     // 'status' => OrderStatus::class,
    //     'midtrans_payment_status' => \App\Enums\MidtransPaymentStatus::class,
    // ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(TransactionItem::class, 'transaction_id', 'id');
    }
}
