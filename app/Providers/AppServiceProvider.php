<?php

namespace App\Providers;

use App\Models\Cart;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('layouts.master', function ($view) {
            $cartItemCount = 0; // Default count
            if (Auth::check()) {
                // Jika pengguna login, hitung jumlah item unik di keranjang
                $cartItemCount = Cart::where('user_id', Auth::id())->count();
            }
            // Meneruskan variabel $cartItemCount ke view
            $view->with('cartItemCount', $cartItemCount);
        });
    }
}
