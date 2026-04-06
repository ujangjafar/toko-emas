<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage; // Tambahkan ini
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Users
        User::create([
            'name' => 'Admin',
            'email' => 'admin@emas.com',
            'password' => Hash::make('password'),
            'role' => 'admin'
        ]);

        User::create([
            'name' => 'User',
            'email' => 'user@example.com',
            'password' => Hash::make('password'),
            'role' => 'user'
        ]);

        // 2. Create Categories
        $categories = ['Cincin Emas', 'Kalung Emas', 'Gelang Emas', 'Anting Emas', 'Liontin Emas'];
        foreach ($categories as $name) {
            Category::create(['name' => $name]);
        }

        // 3. Ambil daftar file dari storage/app/public/products
        // Pastikan kamu sudah menjalankan `php artisan storage:link`
        $files = Storage::disk('public')->files('products');

        if (empty($files)) {
            $this->command->warn("Folder storage/app/public/products kosong! Menggunakan string default.");
        }

        // 4. Create Products
        $categoryIds = Category::pluck('id')->toArray();

        for ($i = 1; $i <= 20; $i++) {
            $productName = 'Emas ' . fake()->unique()->words(2, true);

            $product = Product::create([
                'category_id' => fake()->randomElement($categoryIds),
                'name' => ucwords($productName),
                'description' => fake()->paragraphs(3, true),
                'price' => fake()->numberBetween(500000, 5000000),
                'stock' => fake()->numberBetween(1, 50)
            ]);

            // 5. Ambil 3 gambar secara acak dari folder storage tadi
            for ($j = 1; $j <= 10; $j++) {
                // Jika folder kosong, pakai fallback. Jika ada, ambil acak.
                $randomImage = !empty($files)
                    ? fake()->randomElement($files)
                    : 'products/default.jpg';

                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $randomImage
                ]);
            }
        }
    }
}
