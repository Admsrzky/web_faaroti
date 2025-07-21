<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log; // Ditambahkan untuk logging jika diperlukan

class ProductController extends Controller
{
    public function index()
    {
        // Ambil semua produk, bisa ditambahkan pagination jika jumlahnya sangat banyak
        $allProducts = Product::orderBy('nama_produk', 'asc')->get();
        // Atau dengan pagination:
        // $allProducts = Product::orderBy('nama_produk', 'asc')->paginate(12); // Menampilkan 12 produk per halaman

        return view('product.index', compact('allProducts'));
    }

    public function showProductCategories()
    {
        // DIKOREKSI: Mengubah 'product' menjadi 'products' di with()
        $categories = Category::with(['products' => function ($query) {
            $query->orderBy('nama_produk', 'asc');
        }])
            ->limit(6)
            ->get();

        $popularProducts = Product::where('is_popular', true)
            ->orderBy('nama_produk', 'asc')
            ->limit(6) // Menggunakan limit 6 seperti yang kita sepakati sebelumnya
            ->get();

        // Anda bisa hapus baris dd() ini setelah masalah teratasi
        // dd($categories, $popularProducts);

        return view('welcome', compact('categories', 'popularProducts'));
    }

    public function show(Product $product) // Menggunakan Route Model Binding
    {
        return view('product.detail-product', compact('product')); // Ganti 'product.show' dengan nama view detail produk Anda
    }

    public function contact()
    {
        return view('contact');
    }
}
