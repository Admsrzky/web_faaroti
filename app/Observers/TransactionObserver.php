<?php

namespace App\Observers;

use App\Models\Transaction;
use App\Events\NewTransactionCreated;

class TransactionObserver
{
    /**
     * Handle the Transaction "created" event.
     */
    public function created(Transaction $transaction): void
    {
        // dd('✅ Observer Berjalan!', 'ID Transaksi:', $transaction->id);

        // Panggil event NewTransactionCreated saat transaksi baru dibuat
         session()->flash('new_order_id', $transaction->id);
        session()->flash('new_order_customer', $transaction->name);
    }

}