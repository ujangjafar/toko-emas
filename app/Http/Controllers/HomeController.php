<?php
// app/Http/Controllers/HomeController.php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Display the home page.
     */
    public function index()
    {
        // Ambil 8 produk secara random untuk featured products
        $featuredProducts = Product::with('category', 'images')
            ->inRandomOrder()
            ->limit(8)
            ->get();

        // Ambil 8 produk terbaru
        $latestProducts = Product::with('category', 'images')
            ->latest()
            ->limit(8)
            ->get();

        // Ambil kategori yang memiliki produk beserta gambar produk pertamanya
        $categories = Category::withCount('products')
            ->having('products_count', '>', 0)
            ->with(['products' => function($query) {
                // Ambil 1 produk per kategori beserta gambarnya
                $query->with('images')->limit(1);
            }])
            ->limit(6)
            ->get()
            ->map(function($category) {
                // Tambahkan properti category_image ke setiap kategori
                $firstProduct = $category->products->first();
                
                if ($firstProduct && $firstProduct->images->isNotEmpty()) {
                    $category->category_image = $firstProduct->images->first()->image_path;
                } else {
                    $category->category_image = null; // Tidak ada gambar
                }
                
                // Hapus relasi products untuk menghemat memory (karena sudah tidak diperlukan)
                unset($category->products);
                
                return $category;
            });

        return view('home', compact('featuredProducts', 'latestProducts', 'categories'));
    }

    /**
     * Get categories with images for AJAX (opsional)
     */
    public function getCategoriesWithImages()
    {
        $categories = Category::withCount('products')
            ->having('products_count', '>', 0)
            ->with(['products' => function($query) {
                $query->with('images')->limit(1);
            }])
            ->get()
            ->map(function($category) {
                $firstProduct = $category->products->first();
                
                return [
                    'id' => $category->id,
                    'name' => $category->name,
                    'slug' => $category->slug,
                    'products_count' => $category->products_count,
                    'image' => ($firstProduct && $firstProduct->images->isNotEmpty()) 
                        ? asset('storage/' . $firstProduct->images->first()->image_path)
                        : asset('images/default-category.jpg'),
                    'image_path' => ($firstProduct && $firstProduct->images->isNotEmpty()) 
                        ? $firstProduct->images->first()->image_path
                        : null
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $categories
        ]);
    }
}