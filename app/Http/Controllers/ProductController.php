<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log; // Ditambahkan untuk logging jika diperlukan

class ProductController extends Controller
{
    public function index(Request $request)
    {
        // Mulai query builder
        $query = Product::query();

        // Logika untuk menangani penyortiran dari dropdown
        if ($request->get('sort') == 'harga_asc') {
            $query->orderBy('harga', 'asc');
        } elseif ($request->get('sort') == 'harga_desc') {
            $query->orderBy('harga', 'desc');
        } elseif ($request->get('sort') == 'nama_asc') {
            $query->orderBy('nama_produk', 'asc');
        } else {
            // Urutan default: tampilkan produk terbaru lebih dulu
            $query->latest();
        }

        // Ambil hasil dengan PAGINASI, bukan get()
        // Angka 12 adalah jumlah produk per halaman, bisa Anda ubah
        $allProducts = $query->paginate(12);

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
