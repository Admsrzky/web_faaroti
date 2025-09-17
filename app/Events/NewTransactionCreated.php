<?php

namespace App\Events;

use App\Models\Transaction;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Filament\Notifications\Notification;
use App\Models\User; // Pastikan Anda mengimpor model User

class NewTransactionCreated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Transaction $transaction;

    /**
     * Create a new event instance.
     */
    public function __construct(Transaction $transaction)
    {
        $this->transaction = $transaction;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        // Kirim ke private channel untuk semua user yang terotentikasi.
        // Kita akan otorisasi channel ini nanti.
        return [
            new PrivateChannel('App.Models.User.' . $this->transaction->user_id),
        ];
    }

    /**
     * The event's broadcast name.
     * Ini adalah nama yang akan didengarkan oleh frontend.
     */
    public function broadcastAs(): string
    {
        return 'new-transaction';
    }

    /**
     * Data yang akan dikirim bersama event.
     * Filament akan menggunakan data dari sini untuk membuat notifikasi.
     */
    public function broadcastWith(): array
    {
        return Notification::make()
            ->title('Pesanan Baru Masuk!')
            ->body("Transaksi baru dengan ID: {$this->transaction->trx_id} dari {$this->transaction->name}.")
            ->info()
            ->getBroadcastData();
    }
}
