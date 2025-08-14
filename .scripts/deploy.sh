#!/bin/sh
set -e # Keluar dari skrip jika ada perintah yang gagal

echo "Deployment dimulai..."

# 1. Aktifkan maintenance mode
php artisan down || true

# 2. Ambil kode terbaru dari Git
git pull origin main

# 3. Install dependensi PHP
composer install --no-interaction --prefer-dist --optimize-autoloader --no-dev

# 5. Buat ulang file cache untuk optimasi
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 6. Matikan maintenance mode
php artisan up

echo "Deployment selesai!"
