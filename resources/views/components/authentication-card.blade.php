<div
    class="flex flex-col items-center min-h-screen pt-6 sm:justify-center sm:pt-0 bg-gradient-to-br from-blue-500 to-indigo-600 dark:from-gray-900 dark:to-gray-800">
    <div class="mb-6">
        {{ $logo }}
    </div>

    {{-- Judul Sistem Penjualan Faa Roti --}}
    <h1 class="mb-8 text-3xl font-bold text-center text-white dark:text-gray-200 sm:text-4xl drop-shadow-lg">
        Sistem Penjualan Faa Roti
    </h1>

    <div
        class="w-full px-6 py-8 mt-0 overflow-hidden transition-all duration-300 transform bg-white shadow-2xl sm:max-w-md dark:bg-gray-800 sm:rounded-xl hover:scale-105">
        {{ $slot }}
    </div>
</div>
