<?php
// app/Models/Category.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug'];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($category) {
            $category->slug = Str::slug($category->name);
        });
        static::updating(function ($category) {
            $category->slug = Str::slug($category->name);
        });
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    // Ambil satu gambar produk pertama dari kategori ini
    public function getFirstProductImageAttribute()
    {
        $product = $this->products()->with('images')->first();
        
        if ($product && $product->images->isNotEmpty()) {
            return $product->images->first()->image_path;
        }
        
        return null; // atau return gambar default
    }
}