{{-- resources/views/products/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Koleksi Produk')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold mb-2">Koleksi <span style="color: var(--gold);">Produk</span></h1>
        <p class="text-gray-600">Temukan perhiasan emas impian Anda</p>
    </div>

    <div class="flex flex-col lg:flex-row gap-8">
        <!-- Sidebar Filter -->
        <div class="lg:w-1/4">
            <div class="bg-white rounded-lg shadow-md p-6 sticky top-24">
                <h2 class="font-semibold text-lg mb-4">Filter</h2>
                
                <!-- Categories -->
                <div class="mb-6">
                    <h3 class="font-medium mb-2">Kategori</h3>
                    <div class="space-y-2">
                        <a href="{{ route('products.index') }}" 
                           class="block px-3 py-2 rounded-lg transition {{ !request('category') ? 'bg-gold-light text-gold font-semibold' : 'text-gray-600 hover:bg-gray-100' }}">
                            Semua Produk
                        </a>
                        @foreach($categories as $category)
                            <a href="{{ route('products.index', array_merge(request()->except('category'), ['category' => $category->id])) }}" 
                               class="block px-3 py-2 rounded-lg transition {{ request('category') == $category->id ? 'bg-gold-light text-gold font-semibold' : 'text-gray-600 hover:bg-gray-100' }}">
                                {{ $category->name }} 
                                <span class="text-sm text-gray-400">({{ $category->products_count }})</span>
                            </a>
                        @endforeach
                    </div>
                </div>
                
                <!-- Sort -->
                <div>
                    <h3 class="font-medium mb-2">Urutkan</h3>
                    <select onchange="window.location.href = this.value" 
                            class="w-full border-gray-300 rounded-lg focus:ring-gold focus:border-gold">
                        <option value="{{ route('products.index', array_merge(request()->except('sort'), ['sort' => 'newest'])) }}"
                                {{ request('sort') == 'newest' ? 'selected' : '' }}>
                            Terbaru
                        </option>
                        <option value="{{ route('products.index', array_merge(request()->except('sort'), ['sort' => 'price_asc'])) }}"
                                {{ request('sort') == 'price_asc' ? 'selected' : '' }}>
                            Harga: Rendah ke Tinggi
                        </option>
                        <option value="{{ route('products.index', array_merge(request()->except('sort'), ['sort' => 'price_desc'])) }}"
                                {{ request('sort') == 'price_desc' ? 'selected' : '' }}>
                            Harga: Tinggi ke Rendah
                        </option>
                    </select>
                </div>

                <!-- Active Filters -->
                @if(request('category') || request('search'))
                <div class="mt-4 pt-4 border-t">
                    <h3 class="font-medium mb-2">Filter Aktif</h3>
                    <div class="flex flex-wrap gap-2">
                        @if(request('category'))
                            @php $categoryName = $categories->firstWhere('id', request('category'))->name ?? ''; @endphp
                            <span class="inline-flex items-center bg-gold-light text-gold px-3 py-1 rounded-full text-sm">
                                {{ $categoryName }}
                                <a href="{{ route('products.index', array_merge(request()->except('category'), ['page' => 1])) }}" class="ml-2 hover:text-gold-dark">×</a>
                            </span>
                        @endif
                        @if(request('search'))
                            <span class="inline-flex items-center bg-gold-light text-gold px-3 py-1 rounded-full text-sm">
                                "{{ request('search') }}"
                                <a href="{{ route('products.index', array_merge(request()->except('search'), ['page' => 1])) }}" class="ml-2 hover:text-gold-dark">×</a>
                            </span>
                        @endif
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Product Grid -->
        <div class="lg:w-3/4">
            @if($products->isEmpty())
                <div class="bg-white rounded-lg shadow-md p-12 text-center">
                    <svg class="w-24 h-24 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                    </svg>
                    <p class="text-gray-500 text-lg mb-4">Tidak ada produk yang ditemukan</p>
                    <a href="{{ route('products.index') }}" 
                       class="inline-block bg-gold text-white px-6 py-3 rounded-lg hover:bg-gold-dark transition">
                        Lihat Semua Produk
                    </a>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                    @foreach($products as $product)
                        <div class="group bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition">
                            <a href="{{ route('products.show', $product) }}" class="block relative h-64 overflow-hidden">
                                @if($product->images->isNotEmpty())
                                    <img src="{{ Storage::url($product->images->first()->image_path) }}" 
                                         alt="{{ $product->name }}"
                                         class="w-full h-full object-cover group-hover:scale-110 transition duration-300">
                                @else
                                    <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                                        <span class="text-gray-400">No Image</span>
                                    </div>
                                @endif
                                @if($product->stock < 1)
                                    <div class="absolute top-2 right-2 bg-red-500 text-white px-2 py-1 rounded text-sm">
                                        Habis
                                    </div>
                                @endif
                            </a>
                            
                            <div class="p-4">
                                <a href="{{ route('products.index', ['category' => $product->category_id]) }}" 
                                   class="text-sm text-gold hover:underline mb-1 block">
                                    {{ $product->category->name }}
                                </a>
                                <h3 class="font-semibold text-lg mb-2">
                                    <a href="{{ route('products.show', $product) }}" class="hover:text-gold transition">
                                        {{ $product->name }}
                                    </a>
                                </h3>
                                <p class="text-gold font-bold text-xl mb-3">
                                    Rp {{ number_format($product->price, 0, ',', '.') }}
                                </p>
                                <div class="flex justify-between items-center">
                                    <a href="{{ route('products.show', $product) }}" 
                                       class="text-gray-600 hover:text-gold transition">
                                        Lihat Detail
                                    </a>
                                    @if($product->stock > 0)
                                        <button onclick="addToCart({{ $product->id }})"
                                                class="bg-gold text-white px-4 py-2 rounded-lg hover:bg-gold-dark transition text-sm">
                                            + Keranjang
                                        </button>
                                    @else
                                        <span class="text-red-500 text-sm">Stok Habis</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-8">
                    {{ $products->withQueryString()->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
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
                cancelButtonText: 'Batal'
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
                timer: 1500
            });
            // Update cart count di navbar
            updateCartCount(data.cart_count);
        } else if (data.error) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: data.error
            });
        }
    });
}

function updateCartCount(count) {
    // Cari elemen cart count di navbar dan update
    let cartBadge = document.querySelector('.cart-count-badge');
    if (cartBadge) {
        cartBadge.textContent = count;
        if (count > 0) {
            cartBadge.classList.remove('hidden');
        } else {
            cartBadge.classList.add('hidden');
        }
    }
}
</script>
@endpush