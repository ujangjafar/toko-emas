{{-- resources/views/home.blade.php --}}
@extends('layouts.app')

@section('title', 'Toko Emas Premium')

@section('content')
    <!-- Hero Banner -->
    <div class="relative bg-gradient-to-r from-gray-900 to-black h-[500px] md:h-[600px] overflow-hidden">
        <div class="absolute inset-0">
            <img src="{{ asset('images/hero-banner.png') }}" alt="Hero Banner" class="w-full h-full object-cover opacity-40">
        </div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-full flex items-center">
            <div class="text-white max-w-2xl">
                <h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-bold mb-4">
                    Kemewahan dalam <span style="color: var(--gold);">Setiap Detail</span>
                </h1>
                <p class="text-base sm:text-lg md:text-xl mb-8 text-gray-300">
                    Temukan koleksi perhiasan emas premium kami, dirancang untuk momen spesial Anda.
                </p>
                <a href="{{ route('products.index') }}" 
                   class="inline-block bg-gold text-white px-6 sm:px-8 py-2.5 sm:py-3 rounded-lg text-base sm:text-lg font-semibold hover:bg-gold-dark transition transform hover:scale-105">
                    Lihat Koleksi
                </a>
            </div>
        </div>
    </div>

    <!-- Featured Categories dengan Gambar Produk -->
    <section class="py-12 sm:py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-8 sm:mb-12">
                <h2 class="text-2xl sm:text-3xl font-bold mb-2">Kategori <span class="text-gold">Produk</span></h2>
                <p class="text-sm sm:text-base text-gray-600">Pilih kategori yang Anda minati</p>
            </div>
            
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-3 sm:gap-4">
                @foreach($categories as $category)
                    @php
                        // Ambil gambar produk pertama dari kategori
                        $firstProduct = $category->products->first();
                        $imagePath = $firstProduct && $firstProduct->images->isNotEmpty() 
                            ? $firstProduct->images->first()->image_path 
                            : null;
                    @endphp
                    
                    <a href="{{ route('products.index', ['category' => $category->id]) }}" 
                       class="group relative bg-white rounded-xl shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden">
                        
                        <!-- Gambar Kategori -->
                        <div class="relative aspect-square overflow-hidden">
                            @if($imagePath)
                                <img src="{{ Storage::url($imagePath) }}" 
                                     alt="{{ $category->name }}"
                                     class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                            @else
                                <div class="w-full h-full bg-gradient-to-br from-gold-light to-gold flex items-center justify-center">
                                    <svg class="w-12 h-12 text-white opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                            @endif
                            
                            <!-- Overlay gradient -->
                            <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                            
                            <!-- Tombol Lihat Detail (muncul saat hover) -->
                            <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                <span class="bg-gold text-white px-3 py-1.5 rounded-lg text-sm font-medium transform -translate-y-2 group-hover:translate-y-0 transition duration-300">
                                    Lihat
                                </span>
                            </div>
                        </div>
                        
                        <!-- Info Kategori -->
                        <div class="p-3 text-center">
                            <h3 class="font-semibold text-gray-800 text-sm sm:text-base group-hover:text-gold transition line-clamp-1">
                                {{ $category->name }}
                            </h3>
                            <p class="text-xs sm:text-sm text-gray-500 mt-1">
                                {{ $category->products_count }} Produk
                            </p>
                        </div>
                        
                        <!-- Decorative gold accent -->
                        <div class="absolute bottom-0 left-0 right-0 h-0.5 bg-gold scale-x-0 group-hover:scale-x-100 transition-transform duration-300"></div>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Featured Products -->
    <section class="py-12 sm:py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-8 sm:mb-12">
                <h2 class="text-2xl sm:text-3xl font-bold mb-2">Produk <span class="text-gold">Pilihan</span></h2>
                <p class="text-sm sm:text-base text-gray-600">Koleksi terbaik kami untuk Anda</p>
            </div>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 lg:gap-8">
                @foreach($featuredProducts as $product)
                    <div class="group bg-white rounded-xl shadow-md overflow-hidden hover:shadow-xl transition-all duration-300">
                        <a href="{{ route('products.show', $product) }}" class="block relative aspect-square overflow-hidden">
                            @if($product->images->isNotEmpty())
                                <img src="{{ Storage::url($product->images->first()->image_path) }}" 
                                     alt="{{ $product->name }}"
                                     class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                            @else
                                <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                                    <span class="text-gray-400 text-sm">No Image</span>
                                </div>
                            @endif
                            
                            <!-- Badges -->
                            @if($product->stock < 1)
                                <div class="absolute top-2 right-2 bg-red-500 text-white px-2 py-1 rounded-lg text-xs font-semibold">
                                    Habis
                                </div>
                            @elseif($product->stock < 5)
                                <div class="absolute top-2 right-2 bg-orange-500 text-white px-2 py-1 rounded-lg text-xs font-semibold">
                                    Stok {{ $product->stock }}
                                </div>
                            @endif
                        </a>
                        
                        <div class="p-4">
                            <a href="{{ route('products.index', ['category' => $product->category_id]) }}" 
                               class="text-xs sm:text-sm text-gold hover:underline mb-1 block">
                                {{ $product->category->name }}
                            </a>
                            <h3 class="font-semibold text-sm sm:text-base mb-2 line-clamp-2 h-12">
                                <a href="{{ route('products.show', $product) }}" class="hover:text-gold transition">
                                    {{ $product->name }}
                                </a>
                            </h3>
                            <p class="text-gold font-bold text-base sm:text-lg mb-3">
                                Rp {{ number_format($product->price, 0, ',', '.') }}
                            </p>
                            <div class="flex justify-between items-center">
                                <a href="{{ route('products.show', $product) }}" 
                                   class="text-xs sm:text-sm text-gray-600 hover:text-gold transition">
                                    Lihat Detail
                                </a>
                                @if($product->stock > 0)
                                    <button onclick="addToCart({{ $product->id }})"
                                            class="bg-gold text-white px-3 sm:px-4 py-1.5 sm:py-2 rounded-lg hover:bg-gold-dark transition text-xs sm:text-sm">
                                        + Keranjang
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <div class="text-center mt-8 sm:mt-12">
                <a href="{{ route('products.index') }}" 
                   class="inline-block border-2 border-gold text-gold px-6 sm:px-8 py-2.5 sm:py-3 rounded-lg hover:bg-gold hover:text-white transition text-sm sm:text-base font-semibold">
                    Lihat Semua Produk
                </a>
            </div>
        </div>
    </section>

    <!-- Latest Products -->
    <section class="py-12 sm:py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-8 sm:mb-12">
                <h2 class="text-2xl sm:text-3xl font-bold mb-2">Produk <span class="text-gold">Terbaru</span></h2>
                <p class="text-sm sm:text-base text-gray-600">Koleksi terbaru yang baru tiba</p>
            </div>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 lg:gap-8">
                @foreach($latestProducts as $product)
                    <div class="group bg-white rounded-xl shadow-md overflow-hidden hover:shadow-xl transition-all duration-300">
                        <a href="{{ route('products.show', $product) }}" class="block relative aspect-square overflow-hidden">
                            @if($product->images->isNotEmpty())
                                <img src="{{ Storage::url($product->images->first()->image_path) }}" 
                                     alt="{{ $product->name }}"
                                     class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                            @else
                                <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                                    <span class="text-gray-400 text-sm">No Image</span>
                                </div>
                            @endif
                            
                            <!-- New Badge -->
                            <div class="absolute top-2 left-2 bg-gold text-white px-2 py-1 rounded-lg text-xs font-semibold">
                                New
                            </div>
                        </a>
                        
                        <div class="p-4">
                            <a href="{{ route('products.index', ['category' => $product->category_id]) }}" 
                               class="text-xs sm:text-sm text-gold hover:underline mb-1 block">
                                {{ $product->category->name }}
                            </a>
                            <h3 class="font-semibold text-sm sm:text-base mb-2 line-clamp-2 h-12">
                                <a href="{{ route('products.show', $product) }}" class="hover:text-gold transition">
                                    {{ $product->name }}
                                </a>
                            </h3>
                            <p class="text-gold font-bold text-base sm:text-lg mb-3">
                                Rp {{ number_format($product->price, 0, ',', '.') }}
                            </p>
                            <a href="{{ route('products.show', $product) }}" 
                               class="block text-center bg-gold text-white px-3 sm:px-4 py-1.5 sm:py-2 rounded-lg hover:bg-gold-dark transition text-xs sm:text-sm">
                                Lihat Detail
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endsection

@push('scripts')
<script>
function addToCart(productId) {
    fetch('{{ route("cart.store") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            product_id: productId,
            quantity: 1
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.requires_login) {
            Swal.fire({
                icon: 'info',
                title: 'Login Diperlukan',
                text: 'Silakan login terlebih dahulu untuk menambahkan produk ke keranjang',
                showCancelButton: true,
                confirmButtonText: 'Login',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#D4AF37'
            }).then((result) => {
                if (result.isConfirmed) {
                    sessionStorage.setItem('redirect_after_login', window.location.href);
                    window.location.href = '{{ route("login") }}';
                }
            });
        } else if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: data.message,
                showConfirmButton: false,
                timer: 1500,
                toast: true,
                position: 'top-end'
            });
            // Update cart count di navbar
            if (typeof updateCartCount === 'function') {
                updateCartCount(data.cart_count);
            }
        } else if (data.error) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: data.error,
                confirmButtonColor: '#D4AF37'
            });
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Terjadi kesalahan, silakan coba lagi',
            confirmButtonColor: '#D4AF37'
        });
    });
}

function updateCartCount(count) {
    const cartBadge = document.querySelector('.cart-count-badge');
    if (cartBadge) {
        if (count > 0) {
            cartBadge.textContent = count > 99 ? '99+' : count;
            cartBadge.classList.remove('hidden');
        } else {
            cartBadge.classList.add('hidden');
        }
    }
}
</script>
@endpush

@push('styles')
<style>
/* Utility untuk line-clamp */
.line-clamp-1 {
    display: -webkit-box;
    -webkit-line-clamp: 1;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* Aspect ratio */
.aspect-square {
    aspect-ratio: 1 / 1;
}

/* Smooth transitions */
.transition-all {
    transition-property: all;
    transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
    transition-duration: 300ms;
}
</style>
@endpush