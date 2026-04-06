@extends('layouts.app')

@section('title', $product->name)

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Breadcrumb -->
    <nav class="flex mb-8 text-sm">
        <a href="{{ route('home') }}" class="text-gray-500 hover:text-gold">Home</a>
        <span class="mx-2 text-gray-400">/</span>
        <a href="{{ route('products.index') }}" class="text-gray-500 hover:text-gold">Produk</a>
        <span class="mx-2 text-gray-400">/</span>
        <span class="text-gold">{{ $product->name }}</span>
    </nav>

    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 p-8">
            <!-- Product Images -->
            <div>
                <div class="main-image mb-4">
                    @if($product->images->isNotEmpty())
                        <img src="{{ Storage::url($product->images->first()->image_path) }}" 
                             alt="{{ $product->name }}"
                             id="mainImage"
                             class="w-full h-96 object-cover rounded-lg">
                    @else
                        <div class="w-full h-96 bg-gray-200 rounded-lg flex items-center justify-center">
                            <span class="text-gray-400">No Image</span>
                        </div>
                    @endif
                </div>
                
                @if($product->images->count() > 1)
                    <div class="grid grid-cols-4 gap-2">
                        @foreach($product->images as $image)
                            <img src="{{ Storage::url($image->image_path) }}" 
                                 alt="{{ $product->name }}"
                                 onclick="changeMainImage('{{ Storage::url($image->image_path) }}')"
                                 class="w-full h-24 object-cover rounded-lg cursor-pointer border-2 hover:border-gold transition">
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Product Info -->
            <div>
                <h1 class="text-3xl font-bold mb-2">{{ $product->name }}</h1>
                <p class="text-gray-500 mb-4">SKU: {{ $product->sku }}</p>
                
                <div class="mb-6">
                    <span class="text-4xl font-bold text-gold">
                        Rp {{ number_format($product->price, 0, ',', '.') }}
                    </span>
                </div>

                <div class="mb-6">
                    <div class="flex items-center mb-4">
                        <span class="text-gray-600 mr-2">Stok:</span>
                        @if($product->stock > 0)
                            <span class="text-green-600 font-semibold">{{ $product->stock }} tersedia</span>
                        @else
                            <span class="text-red-600 font-semibold">Habis</span>
                        @endif
                    </div>

                    @if($product->stock > 0)
                        <div class="flex items-center space-x-4 mb-6">
                            <div class="flex items-center border rounded-lg">
                                <button onclick="decrementQuantity()" class="px-4 py-2 text-gray-600 hover:bg-gray-100">-</button>
                                <input type="number" id="quantity" value="1" min="1" max="{{ $product->stock }}" 
                                       class="w-16 text-center border-0 focus:ring-0">
                                <button onclick="incrementQuantity()" class="px-4 py-2 text-gray-600 hover:bg-gray-100">+</button>
                            </div>
                            
                            <button onclick="addToCart()" 
                                    class="flex-1 bg-gold text-white px-6 py-3 rounded-lg hover:bg-gold-dark transition font-semibold">
                                Tambah ke Keranjang
                            </button>
                        </div>
                    @endif
                </div>

                <div class="border-t pt-6">
                    <h2 class="font-semibold text-lg mb-2">Deskripsi Produk</h2>
                    <div class="text-gray-600 leading-relaxed">
                        {{ $product->description }}
                    </div>
                </div>

                <div class="border-t mt-6 pt-6">
                    <h2 class="font-semibold text-lg mb-2">Kategori</h2>
                    <a href="{{ route('products.index', ['category' => $product->category_id]) }}" 
                       class="inline-block bg-gray-100 text-gray-700 px-4 py-2 rounded-lg hover:bg-gold hover:text-white transition">
                        {{ $product->category->name }}
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Related Products -->
    @if($relatedProducts->isNotEmpty())
        <section class="mt-12">
            <h2 class="text-2xl font-bold mb-6">Produk <span style="color: var(--gold);">Terkait</span></h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($relatedProducts as $related)
                    <div class="group bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition">
                        <div class="relative h-48 overflow-hidden">
                            @if($related->images->isNotEmpty())
                                <img src="{{ Storage::url($related->images->first()->image_path) }}" 
                                     alt="{{ $related->name }}"
                                     class="w-full h-full object-cover group-hover:scale-110 transition duration-300">
                            @endif
                        </div>
                        <div class="p-4">
                            <h3 class="font-semibold mb-2">{{ $related->name }}</h3>
                            <p class="text-gold font-bold">
                                Rp {{ number_format($related->price, 0, ',', '.') }}
                            </p>
                            <a href="{{ route('products.show', $related) }}" 
                               class="mt-2 block text-center text-sm text-gold hover:underline">
                                Lihat Detail
                            </a>
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
function changeMainImage(src) {
    document.getElementById('mainImage').src = src;
}

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

function addToCart() {
    let quantity = document.getElementById('quantity').value;
    
    fetch('{{ route("cart.store") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            product_id: {{ $product->id }},
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
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '{{ route("login") }}';
                }
            });
        } else if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: data.message,
                showConfirmButton: false,
                timer: 1500
            });
        } else if (data.error) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: data.error
            });
        }
    });
}
</script>
@endpush