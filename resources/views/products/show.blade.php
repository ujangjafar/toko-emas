{{-- resources/views/products/show.blade.php --}}
@extends('layouts.app')

@section('title', $product->name . ' - Toko Emas Premium')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Breadcrumb -->
    <nav class="flex mb-8 text-sm" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="{{ route('home') }}" class="text-gray-500 hover:text-gold transition">Home</a>
            </li>
            <li>
                <div class="flex items-center">
                    <svg class="w-4 h-4 text-gray-400 mx-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                    </svg>
                    <a href="{{ route('products.index') }}" class="text-gray-500 hover:text-gold transition ml-1 md:ml-2">Produk</a>
                </div>
            </li>
            <li>
                <div class="flex items-center">
                    <svg class="w-4 h-4 text-gray-400 mx-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                    </svg>
                    <a href="{{ route('products.index', ['category' => $product->category_id]) }}" class="text-gray-500 hover:text-gold transition ml-1 md:ml-2">
                        {{ $product->category->name }}
                    </a>
                </div>
            </li>
            <li aria-current="page">
                <div class="flex items-center">
                    <svg class="w-4 h-4 text-gray-400 mx-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="text-gold ml-1 md:ml-2 font-medium">{{ $product->name }}</span>
                </div>
            </li>
        </ol>
    </nav>

    <!-- Product Detail -->
    <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 p-8">
            <!-- Product Images Section -->
            <div>
                <!-- Main Image -->
                <div class="main-image mb-4 bg-gray-50 rounded-xl overflow-hidden border">
                    @if($product->images->isNotEmpty())
                        <img src="{{ Storage::url($product->images->first()->image_path) }}" 
                             alt="{{ $product->name }}"
                             id="mainImage"
                             class="w-full h-96 object-contain p-4">
                    @else
                        <div class="w-full h-96 bg-gray-100 rounded-lg flex items-center justify-center">
                            <svg class="w-24 h-24 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                    @endif
                </div>
                
                <!-- Thumbnail Images -->
                @if($product->images->count() > 1)
                    <div class="grid grid-cols-5 gap-2">
                        @foreach($product->images as $index => $image)
                            <button onclick="changeMainImage('{{ Storage::url($image->image_path) }}')"
                                    class="thumbnail-btn border-2 rounded-lg overflow-hidden hover:border-gold transition focus:outline-none {{ $index === 0 ? 'border-gold' : 'border-transparent' }}">
                                <img src="{{ Storage::url($image->image_path) }}" 
                                     alt="{{ $product->name }} - Image {{ $index + 1 }}"
                                     class="w-full h-20 object-cover">
                            </button>
                        @endforeach
                    </div>
                @endif

                <!-- Share & Save -->
                <div class="flex items-center justify-between mt-6 pt-4 border-t">
                    <div class="flex items-center space-x-4">
                        <button onclick="shareProduct()" class="text-gray-500 hover:text-gold transition flex items-center space-x-1">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"></path>
                            </svg>
                            <span class="text-sm">Bagikan</span>
                        </button>
                        <button onclick="saveToWishlist({{ $product->id }})" class="text-gray-500 hover:text-gold transition flex items-center space-x-1">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                            </svg>
                            <span class="text-sm">Simpan</span>
                        </button>
                    </div>
                    <span class="text-sm text-gray-500">SKU: {{ $product->sku }}</span>
                </div>
            </div>

            <!-- Product Info Section -->
            <div class="lg:pl-8">
                <!-- Category -->
                <a href="{{ route('products.index', ['category' => $product->category_id]) }}" 
                   class="inline-block text-sm text-gold hover:underline mb-2">
                    {{ $product->category->name }}
                </a>
                
                <!-- Title -->
                <h1 class="text-3xl font-bold text-gray-900 mb-4">{{ $product->name }}</h1>
                
                <!-- Price -->
                <div class="mb-6">
                    <div class="flex items-baseline">
                        <span class="text-4xl font-bold text-gold">
                            Rp {{ number_format($product->price, 0, ',', '.') }}
                        </span>
                        @if($product->stock < 5 && $product->stock > 0)
                            <span class="ml-4 text-sm text-orange-600 bg-orange-100 px-3 py-1 rounded-full">
                                Stok tersisa {{ $product->stock }}
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Stock Status -->
                <div class="mb-6">
                    <div class="flex items-center space-x-2">
                        @if($product->stock > 0)
                            <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="text-green-600 font-medium">Stok Tersedia ({{ $product->stock }})</span>
                        @else
                            <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            <span class="text-red-600 font-medium">Stok Habis</span>
                        @endif
                    </div>
                </div>

                <!-- Quantity Selector -->
                @if($product->stock > 0)
                    <div class="mb-6">
                        <label for="quantity" class="block text-sm font-medium text-gray-700 mb-2">
                            Jumlah
                        </label>
                        <div class="flex items-center space-x-3">
                            <div class="flex items-center border-2 rounded-lg">
                                <button type="button" onclick="decrementQuantity()" 
                                        class="px-4 py-2 text-gray-600 hover:bg-gray-100 transition rounded-l-lg">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                    </svg>
                                </button>
                                <input type="number" 
                                       id="quantity" 
                                       name="quantity" 
                                       value="1" 
                                       min="1" 
                                       max="{{ $product->stock }}"
                                       class="w-16 text-center border-0 focus:ring-0 text-lg font-semibold">
                                <button type="button" onclick="incrementQuantity()" 
                                        class="px-4 py-2 text-gray-600 hover:bg-gray-100 transition rounded-r-lg">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                    </svg>
                                </button>
                            </div>
                            <span class="text-sm text-gray-500">Maks. {{ $product->stock }} item</span>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex flex-col space-y-3 mb-8">
                        <button onclick="addToCart()" 
                                class="w-full bg-gold text-white px-6 py-4 rounded-lg hover:bg-gold-dark transition font-semibold text-lg flex items-center justify-center space-x-2">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            <span>Tambah ke Keranjang</span>
                        </button>
                        
                        <button onclick="buyNow()" 
                                class="w-full border-2 border-gold text-gold px-6 py-4 rounded-lg hover:bg-gold hover:text-white transition font-semibold text-lg">
                            Beli Langsung
                        </button>
                    </div>
                @else
                    <div class="mb-8">
                        <button disabled 
                                class="w-full bg-gray-300 text-gray-500 px-6 py-4 rounded-lg font-semibold text-lg cursor-not-allowed">
                            Stok Habis
                        </button>
                    </div>
                @endif

                <!-- Product Description -->
                <div class="border-t pt-6">
                    <h2 class="text-xl font-semibold mb-4">Deskripsi Produk</h2>
                    <div class="prose prose-sm max-w-none text-gray-600 leading-relaxed">
                        {!! nl2br(e($product->description)) !!}
                    </div>
                </div>

                <!-- Product Features -->
                <div class="border-t mt-6 pt-6">
                    <h2 class="text-xl font-semibold mb-4">Keunggulan Produk</h2>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="flex items-center space-x-2 text-gray-600">
                            <svg class="w-5 h-5 text-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span>Emas Asli 24K</span>
                        </div>
                        <div class="flex items-center space-x-2 text-gray-600">
                            <svg class="w-5 h-5 text-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span>Desain Eksklusif</span>
                        </div>
                        <div class="flex items-center space-x-2 text-gray-600">
                            <svg class="w-5 h-5 text-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span>Garansi 100%</span>
                        </div>
                        <div class="flex items-center space-x-2 text-gray-600">
                            <svg class="w-5 h-5 text-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span>Pengiriman Aman</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Related Products -->
    @if($relatedProducts->isNotEmpty())
        <section class="mt-16">
            <h2 class="text-2xl font-bold mb-8 text-center">Produk <span class="text-gold">Terkait</span></h2>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($relatedProducts as $related)
                    <div class="group bg-white rounded-xl shadow-md overflow-hidden hover:shadow-xl transition">
                        <a href="{{ route('products.show', $related) }}" class="block relative h-48 overflow-hidden">
                            @if($related->images->isNotEmpty())
                                <img src="{{ Storage::url($related->images->first()->image_path) }}" 
                                     alt="{{ $related->name }}"
                                     class="w-full h-full object-cover group-hover:scale-110 transition duration-300">
                            @else
                                <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                                    <span class="text-gray-400">No Image</span>
                                </div>
                            @endif
                        </a>
                        <div class="p-4">
                            <a href="{{ route('products.index', ['category' => $related->category_id]) }}" 
                               class="text-xs text-gold hover:underline">
                                {{ $related->category->name }}
                            </a>
                            <h3 class="font-semibold mt-1 mb-2">
                                <a href="{{ route('products.show', $related) }}" class="hover:text-gold transition">
                                    {{ $related->name }}
                                </a>
                            </h3>
                            <p class="text-gold font-bold">
                                Rp {{ number_format($related->price, 0, ',', '.') }}
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
    @endif
</div>
@endsection

@push('scripts')
<script>
// Change main image when thumbnail clicked
function changeMainImage(src) {
    document.getElementById('mainImage').src = src;
    
    // Update thumbnail active state
    document.querySelectorAll('.thumbnail-btn').forEach(btn => {
        btn.classList.remove('border-gold');
        btn.classList.add('border-transparent');
    });
    event.currentTarget.classList.remove('border-transparent');
    event.currentTarget.classList.add('border-gold');
}

// Quantity handlers
function incrementQuantity() {
    let input = document.getElementById('quantity');
    let max = parseInt(input.max);
    let value = parseInt(input.value);
    if (value < max) {
        input.value = value + 1;
    }
}

function decrementQuantity() {
    let input = document.getElementById('quantity');
    let value = parseInt(input.value);
    if (value > 1) {
        input.value = value - 1;
    }
}

// Add to cart function
function addToCart() {
    let quantity = document.getElementById('quantity').value;
    let productId = {{ $product->id }};
    
    fetch('{{ route("cart.store") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            product_id: productId,
            quantity: quantity
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
                    // Save current URL to redirect back after login
                    sessionStorage.setItem('redirect_after_login', window.location.href);
                    window.location.href = '{{ route("login") }}';
                }
            });
        } else if (data.success) {
            // Trigger cart update event
            document.dispatchEvent(new CustomEvent('cart-updated', {
                detail: { count: data.cart_count }
            }));
            
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: data.message,
                showConfirmButton: false,
                timer: 1500,
                position: 'top-end',
                toast: true
            });
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

// Buy now function
function buyNow() {
    addToCart().then(() => {
        window.location.href = '{{ route("cart.index") }}';
    });
}

// Share product
function shareProduct() {
    if (navigator.share) {
        navigator.share({
            title: '{{ $product->name }}',
            text: '{{ Str::limit($product->description, 100) }}',
            url: window.location.href
        })
        .catch(console.error);
    } else {
        // Fallback - copy to clipboard
        navigator.clipboard.writeText(window.location.href).then(() => {
            Swal.fire({
                icon: 'success',
                title: 'Link Disalin!',
                text: 'Link produk telah disalin ke clipboard',
                showConfirmButton: false,
                timer: 2000,
                position: 'top-end',
                toast: true
            });
        });
    }
}

// Save to wishlist (placeholder - implement later)
function saveToWishlist(productId) {
    Swal.fire({
        icon: 'info',
        title: 'Info',
        text: 'Fitur wishlist akan segera hadir!',
        confirmButtonColor: '#D4AF37'
    });
}

// Initialize quantity input validation
document.getElementById('quantity').addEventListener('change', function() {
    let value = parseInt(this.value);
    let min = parseInt(this.min);
    let max = parseInt(this.max);
    
    if (value < min) this.value = min;
    if (value > max) this.value = max;
});

// Refresh cart count on page load
document.addEventListener('DOMContentLoaded', function() {
    @auth
        if (typeof refreshCartCount === 'function') {
            refreshCartCount();
        }
    @endauth
});
</script>
@endpush

@push('styles')
<style>
/* Thumbnail active state */
.thumbnail-btn.border-gold {
    border-width: 2px;
}

/* Quantity input spinner removal */
input[type=number]::-webkit-inner-spin-button,
input[type=number]::-webkit-outer-spin-button {
    -webkit-appearance: none;
    margin: 0;
}
input[type=number] {
    -moz-appearance: textfield;
}

/* Product description prose */
.prose {
    line-height: 1.6;
}
</style>
@endpush