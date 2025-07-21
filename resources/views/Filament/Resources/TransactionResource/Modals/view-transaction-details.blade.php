<div class="p-4 space-y-4 bg-white rounded-lg shadow dark:bg-gray-800">
    <h3 class="text-xl font-bold text-gray-900 dark:text-white">Detail Transaksi #{{ $transaction->trx_id }}</h3>

    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
        <div>
            <p class="text-sm text-gray-600 dark:text-gray-400">Nama Customer:</p>
            <p class="text-base font-semibold text-gray-800 dark:text-gray-200">{{ $transaction->name }}</p>
        </div>
        <div>
            <p class="text-sm text-gray-600 dark:text-gray-400">Email:</p>
            <p class="text-base font-semibold text-gray-800 dark:text-gray-200">{{ $transaction->email }}</p>
        </div>
        <div>
            <p class="text-sm text-gray-600 dark:text-gray-400">Telepon:</p>
            <p class="text-base font-semibold text-gray-800 dark:text-gray-200">{{ $transaction->phone }}</p>
        </div>
        <div>
            <p class="text-sm text-gray-600 dark:text-gray-400">Total Transaksi:</p>
            <p class="text-base font-semibold text-primary-600 dark:text-primary-400">Rp
                {{ number_format($transaction->grand_total, 0, ',', '.') }}</p>
        </div>
        <div>
            <p class="text-sm text-gray-600 dark:text-gray-400">Status Pesanan:</p>
            <span
                class="px-3 py-1 text-xs font-semibold rounded-full
                @php
$statusColorClass = 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300'; // Default
                    switch ($transaction->status) {
                        case 'pending': $statusColorClass = 'bg-yellow-100 text-yellow-800 dark:bg-yellow-700 dark:text-yellow-200'; break;
                        case 'dikirim': $statusColorClass = 'bg-blue-100 text-blue-800 dark:bg-blue-700 dark:text-blue-200'; break;
                        case 'selesai': $statusColorClass = 'bg-green-100 text-green-800 dark:bg-green-700 dark:text-green-200'; break;
                        case 'dibatalkan': $statusColorClass = 'bg-red-100 text-red-800 dark:bg-red-700 dark:text-red-200'; break;
                    } @endphp
                {{ $statusColorClass }}">
                {{ ucfirst($transaction->status) }}
            </span>
        </div>
        <div>
            <p class="text-sm text-gray-600 dark:text-gray-400">Status Pembayaran:</p>
            <span
                class="px-3 py-1 text-xs font-semibold rounded-full
                @php
$paymentStatusText = ucfirst($transaction->payment_status);
                    $paymentStatusColorClass = 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300'; // Default
                    switch ($transaction->payment_status) {
                        case 'pending': $paymentStatusColorClass = 'bg-yellow-100 text-yellow-800 dark:bg-yellow-700 dark:text-yellow-200'; break;
                        case 'success':
                        case 'settlement':
                        case 'capture': $paymentStatusColorClass = 'bg-green-100 text-green-800 dark:bg-green-700 dark:text-green-200'; $paymentStatusText = 'Success'; break;
                        case 'deny':
                        case 'expire':
                        case 'cancel':
                        case 'refund': $paymentStatusColorClass = 'bg-red-100 text-red-800 dark:bg-red-700 dark:text-red-200'; break;
                        case 'challenge': $paymentStatusColorClass = 'bg-orange-100 text-orange-800 dark:bg-orange-700 dark:text-orange-200'; break; // Changed to orange for 'challenge'
                        case 'authorize': $paymentStatusColorClass = 'bg-blue-100 text-blue-800 dark:bg-blue-700 dark:text-blue-200'; break;
                    } @endphp
                {{ $paymentStatusColorClass }}">
                {{ $paymentStatusText }}
            </span>
        </div>
        <div>
            <p class="text-sm text-gray-600 dark:text-gray-400">Tipe Pembayaran:</p>
            <p class="text-base font-semibold text-gray-800 dark:text-gray-200">
                {{ $transaction->payment_type ?? 'N/A' }}</p>
        </div>
        <div>
            <p class="text-sm text-gray-600 dark:text-gray-400">Tanggal Transaksi:</p>
            <p class="text-base font-semibold text-gray-800 dark:text-gray-200">
                {{ $transaction->created_at->format('d M Y H:i') }}</p>
        </div>
    </div>

    <h4 class="mt-6 mb-2 text-lg font-bold text-gray-900 dark:text-white">Item Pesanan:</h4>
    @forelse ($transaction->items as $item)
        <div
            class="flex items-center gap-6 p-3 mb-2 border border-gray-200 rounded-lg shadow-sm bg-gray-50 dark:bg-gray-700 dark:border-gray-600">
            @if ($item->product_photo)
                <img src="{{ asset('storage/' . $item->product_photo) }}" alt="{{ $item->product_name }}"
                    class="object-cover w-10 h-10 mr-3 rounded-md">
            @endif
            <div class="flex-1">
                <p class="font-bold text-gray-900 dark:text-white">{{ $item->quantity }}x {{ $item->product_name }}
                </p>
                <p class="text-sm text-gray-600 dark:text-gray-400">Rp
                    {{ number_format($item->price_at_purchase, 0, ',', '.') }} / item
                </p>
            </div>
            <p class="font-bold text-gray-800 dark:text-gray-200">Rp
                {{ number_format($item->sub_total_item, 0, ',', '.') }}</p>
        </div>
    @empty
        <p class="text-gray-600 dark:text-gray-400">Tidak ada item dalam transaksi ini.</p>
    @endforelse

    <h4 class="mt-6 mb-2 text-lg font-bold text-gray-900 dark:text-white">Alamat Pengiriman:</h4>
    <p class="text-gray-700 dark:text-white">{{ $transaction->address }}, {{ $transaction->city }},
        {{ $transaction->post_code }}</p>
</div>
