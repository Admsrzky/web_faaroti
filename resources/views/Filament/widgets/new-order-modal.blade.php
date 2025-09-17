<x-filament-widgets::widget>
    <div
        x-data
        x-init="
            $nextTick(() => {
                // Cek jika properti Livewire 'newOrder' tidak kosong
                if ($wire.newOrder) {
                    // Buka modal dari action 'viewOrderAction'
                    $wire.mountAction('viewOrder');
                }
            })
        "
    >
        {{-- Action ini dirender tapi disembunyikan, hanya untuk pemicu modal --}}
    </div>
</x-filament-widgets::widget>
